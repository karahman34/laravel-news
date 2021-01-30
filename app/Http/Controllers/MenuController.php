<?php

namespace App\Http\Controllers;

use App\Exports\MenusExport;
use App\Helpers\MenuHelper;
use App\Http\Requests\MenuRequest;
use App\Imports\MenusImport;
use App\Models\Menu;
use App\Traits\ExcelTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    use ExcelTrait;

    /**
     * Display a listing of the resource.
     *
     * @param   Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Menu::class);

        if (!$request->wantsJson()) {
            return view('pages.administrator.menus', [
                'title' => 'Menus'
            ]);
        }

        $auth = $request->user();

        return DataTables::of(Menu::query())
                            ->addColumn('actions', function (Menu $menu) use ($auth) {
                                $permissionsButton = '';
                                $editButton = '';
                                $deleteButton = '';
                                
                                if ($auth->can('syncPermissions', $menu)) {
                                    $permissionsButton = '<a href="'.route('administrator.menus.permissions.index', ['menu' => $menu]).'" class="btn btn-info btn-modal-trigger" data-modal="#menu-permissions-modal"><i class="fas fa-lock"></i></a>';
                                }

                                if ($auth->can('update', $menu)) {
                                    $editButton = '<a href="'.route('administrator.menus.edit', ['menu' => $menu]).'" class="btn btn-warning btn-modal-trigger" data-modal="#form-menu-modal"><i class="fas fa-edit"></i></a>';
                                }
                                
                                if ($auth->can('delete', $menu)) {
                                    $deleteButton = '<a href="'.route('administrator.menus.destroy', ['menu' => $menu]).'" class="btn btn-danger delete-prompt-trigger has-datatable" data-datatable="#menu-datatable" data-item-name="'.$menu->name.'"><i class="fas fa-trash"></i></a>';
                                }

                                return $permissionsButton . $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
    * Export Menus data.
    *
    * @param   Request  $request
    *
    * @return  mixed
    */
    public function export(Request $request)
    {
        $allowedFormats = ['xlsx', 'csv'];

        if ($request->get('export') != 1) {
            return view('components.export-modal', [
                'action' => route('administrator.menus.export'),
                'formats' => $allowedFormats,
            ]);
        }

        return $this->exportFile($request, new MenusExport($request->take), 'menus', $allowedFormats);
    }

    /**
     * Import Menus data.
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function import(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('components.import-modal', [
                'action' => route('administrator.menus.import'),
                'dataTable' => '#menu-datatable',
            ]);
        }

        $allowedFormats = ['xlsx', 'csv'];

        return $this->importFile($request, new MenusImport, $allowedFormats);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Menu::class);

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

        // Set menu path.
        $payload['path'] = MenuHelper::setMenuPath($payload['path'], $payload['parent_id']);
        if (!is_string($payload['path'])) {
            return $payload['path'];
        }

        // Create menu model.
        $menu = Menu::create($payload);

        return response()->json([
            'ok' => true,
            'message' => 'Menu created.',
            'data' => $menu
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        $this->authorize('update', $menu);

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
     * Update permissions name.
     *
     * @param   string  $oldName
     * @param   string  $newName
     *
     * @return  void
     */
    private function updatePermissionNames(string $oldName, string $newName)
    {
        $permissions = Permission::where('name', 'like', $oldName. '-%')->get();
        $permissions->each(function (Permission $permission) use ($newName) {
            $name = explode('-', $permission->name);
            $name[0] = $newName;
            $permission->name = implode('-', $name);
            $permission->save();
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
        $oldName = $menu->name;
        $payload = $menuRequest->only([
            'parent_id',
            'name',
            'icon',
            'has_sub_menus',
        ]);

        // Set menu path.
        if (isset($menuRequest->path) && strlen($menuRequest->path) > 0) {
            $payload['path'] = MenuHelper::setMenuPath($menuRequest->path, $payload['parent_id']);
            if (!is_string($payload['path'])) {
                return $payload['path'];
            }
        }

        // Update menu.
        $menu->update($payload);

        // Update child menus path.
        if ($menu->has_sub_menus === 'Y' && $oldPath !== $menu->path) {
            $this->updateChildMenus($menu->id, $oldPath, $menu->path);
        }

        // Update permissions name.
        if ($oldName !== $menu->name) {
            $this->updatePermissionNames($oldName, $menu->name);
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
        $this->authorize('delete', $menu);

        $menuName = $menu->name;

        // Delete menu model.
        $menu->delete();

        // Delete permissions.
        Permission::where('name', 'like', $menuName. '-%')->delete();
        
        return response()->json([
            'ok' => true,
            'message' => 'Menu deleted.',
            'data' => $menu
        ]);
    }
}
