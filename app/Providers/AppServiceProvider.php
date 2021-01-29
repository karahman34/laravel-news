<?php

namespace App\Providers;

use App\Helpers\MenuHelper;
use App\Models\Menu;
use App\Models\News;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private function setAdminMenus()
    {
        View::composer('*', function ($view) {
            $segment1 = request()->segment(1);

            if ($segment1 === 'administrator' && Auth::check()) {
                // Get Auth user.
                $auth = Auth::user();

                // Get menus & sub menus.
                $menus = Menu::whereNull('parent_id')
                            ->where('path', 'like', '/administrator/%')
                            ->get();
                $subMenus = Menu::whereIn('parent_id', $menus->pluck('id'))
                                ->get();

                // Group & merge sub menus with parent menus.
                $subMenus->groupBy('parent_id')->each(function ($items, $parentId) use ($menus, $auth) {
                    $targetMenu = $menus->firstWhere('id', $parentId);
                    if ($targetMenu) {
                        $targetMenu['sub_menus'] = $items->filter(function (Menu $subMenu) use ($auth) {
                            return $auth->can(MenuHelper::setPermissionName($subMenu->name, 'view'));
                        });
                    }
                });

                // Filter menus.
                $menus = $menus->filter(function (Menu $menu) use ($auth) {
                    if ($menu->has_sub_menus === 'N') {
                        return $auth->can(MenuHelper::setPermissionName($menu->name, 'view'));
                    } else {
                        return $menu->sub_menus->count() > 0;
                    }
                });

                // Get active menu.
                $currentPath = request()->getPathInfo();
                $activeMenu = $menus->firstWhere('path', $currentPath);
                if (!$activeMenu) {
                    $activeMenu = $subMenus->firstWhere('path', $currentPath);
                }

                $view->with('menus', $menus);
                $view->with('activeMenu', $activeMenu);
                config(['global.menus' => $menus]);
            }
        });
    }

    private function setPopularTags($view)
    {
        $tags = Tag::select('name')
                        ->orderByDesc('views')
                        ->orderByDesc('created_at')
                        ->limit(20)
                        ->get();
                
        $view->with('popularTags', $tags);
    }

    private function setPopularNews($view)
    {
        $news = News::select('id', 'banner_image', DB::raw('IF(LENGTH(title) > 75, CONCAT(SUBSTR(title, 1, 75), "..."), title) as title'), 'created_at')
                        ->where('status', 'publish')
                        ->whereBetween('created_at', [Carbon::now()->subDays(3), Carbon::now()])
                        ->orderByDesc('views')
                        ->orderByDesc('created_at')
                        ->limit(10)
                        ->get();

        $view->with('popularNews', $news);
    }

    private function welcomePage()
    {
        View::composer('*', function ($view) {
            $segment1 = request()->segment(1);

            if ($segment1 !== 'administrator') {
                $this->setPopularTags($view);
                $this->setPopularNews($view);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setAdminMenus();
        $this->welcomePage();
    }
}
