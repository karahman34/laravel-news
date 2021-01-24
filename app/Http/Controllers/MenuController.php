<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param   Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->wantsJson()) {
            $menus = config('global.menus');
            $menu = $menus->firstWhere('path', $request->getPathInfo());

            return view('pages.menus', [
                'title' => 'Menus',
                'menu' => $menu,
            ]);
        }

        return DataTables::of(Menu::query())
                            ->addColumn('actions', function ($menu) {
                                $editButton = '<a href="'.route('administrator.menus.edit', ['menu' => $menu]).'" class="btn btn-warning btn-modal-trigger" data-modal="#form-menu-modal"><i class="fas fa-edit"></i></a>';
                                
                                $deleteButton = '<a href="'.route('administrator.menus.destroy', ['menu' => $menu]).'" class="btn btn-danger delete-prompt-trigger has-datatable" data-datatable="#menu-datatable" data-item-name="'.$menu->name.'"><i class="fas fa-trash"></i></a>';

                                return $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('components.menu.form-modal');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  MenuRequest  $menuRequest
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $menuRequest)
    {
        $payload = $menuRequest->only([
            'parent_id',
            'name',
            'icon',
            'path',
            'has_sub_menus',
        ]);

        if (is_numeric($payload['parent_id'])) {
            $parentMenu = Menu::select('id', 'path')->whereId($payload['parent_id'])->first();

            if (!$parentMenu) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Parent menu is invalid.',
                ], 404);
            }

            $payload['path'] = $parentMenu->path . $payload['path'];
        }

        $menu = Menu::create($payload);

        return response()->json([
            'ok' => true,
            'message' => 'Menu created.',
            'data' => $menu
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        return view('components.menu.form-modal', [
            'menu' => $menu
        ]);
    }

    /**
     * Update child menus path.
     *
     * @param   int|string  $parentId
     * @param   string  $oldPath
     * @param   string  $newPath
     *
     * @return  void
     */
    private function updateChildMenus(int $parentId, string $oldPath, string $newPath)
    {
        $childMenus = Menu::where('parent_id', $parentId)->get();
        $childMenus->each(function (Menu $childMenu) use ($oldPath, $newPath) {
            $childMenu->path = str_replace($oldPath, $newPath, $childMenu->path);
            $childMenu->save();
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  MenuRequest  $menuRequest
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(MenuRequest $menuRequest, Menu $menu)
    {
        $oldPath = $menu->path;
        $payload = $menuRequest->only([
            'parent_id',
            'name',
            'icon',
            'has_sub_menus',
        ]);

        if (isset($menuRequest->path) && strlen($menuRequest->path) > 0) {
            $payload['path'] = $menuRequest->path;

            if (is_numeric($payload['parent_id'])) {
                $parentMenu = Menu::select('id', 'path')->whereId($payload['parent_id'])->first();
                
                if (!$parentMenu) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Parent menu is invalid.',
                    ], 404);
                }
    
                $payload['path'] = $parentMenu->path . $payload['path'];
            }
        }

        $menu->update($payload);

        if ($menu->has_sub_menus === 'Y') {
            $this->updateChildMenus($menu->id, $oldPath, $menu->path);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Menu updated.',
            'data' => $menu
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Menu deleted.',
            'data' => $menu
        ]);
    }
}
