<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private function setGlobalMenus()
    {
        $segment1 = request()->segment(1);
        $menus = collect([]);
        $subMenus = collect([]);

        if ($segment1 === 'administrator') {
            $menus = Menu::whereNull('parent_id')
                            ->where('path', 'like', '/administrator/%')
                            ->get();
            $subMenus = Menu::whereNotNull('parent_id')->get();

            $subMenus->groupBy('parent_id')->each(function ($items, $parentId) use ($menus) {
                $targetMenu = $menus->firstWhere('id', $parentId);
                if ($targetMenu) {
                    $targetMenu['sub_menus'] = $items;
                }
            });
        }

        $currentPath = request()->getPathInfo();
        $activeMenu = $menus->firstWhere('path', $currentPath);
        if (!$activeMenu) {
            $activeMenu = $subMenus->firstWhere('path', $currentPath);
        }

        View::share('menus', $menus);
        View::share('activeMenu', $activeMenu);
        config(['global.menus' => $menus]);
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
        $this->setGlobalMenus();
    }
}
