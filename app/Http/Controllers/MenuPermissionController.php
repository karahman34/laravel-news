<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuPermissionRequest;
use App\Models\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class MenuPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param   Request  $request
     * @param   Menu     $menu
     *
     * @return  \Illuminate\Http\Response
     */
    public function index(Request $request, Menu $menu)
    {
        if (!$request->wantsJson()) {
            return view('components.menu.permissions-modal', [
                'menu' => $menu
            ]);
        }

        $permissions = Permission::query();
        $permissions->where('name', 'like', $menu->name . '-%');

        return DataTables::of($permissions)
                            ->addColumn('actions', function (Permission $permission) use ($menu) {
                                $editButton = '<a href="#" class="btn btn-warning" data-permission-id="'.$permission->id.'" data-permission-name="'.$permission->name.'"><i class="fas fa-edit"></i></a>';
                                
                                $deleteButton = '<a href="'.route('administrator.menus.permissions.destroy', ['menu' => $menu, 'permission' => $permission]).'" class="btn btn-danger delete-prompt-trigger has-datatable" data-datatable="#menu-permissions-datatable" data-item-name="'.$permission->name.'"><i class="fas fa-trash"></i></a>';

                                return $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  MenuPermissionRequest    $menuPermissionRequest
     * @return \Illuminate\Http\Response
     */
    public function store(MenuPermissionRequest $menuPermissionRequest, Menu $menu)
    {
        $permission = Permission::create([
            'name' => $menu->name . '-' . $menuPermissionRequest->name
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'New Permission added.',
            'data' => $permission
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  MenuPermissionRequest    $menuPermissionRequest
     * @param  \App\Models\Menu  $menu
     * @param   Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(MenuPermissionRequest $menuPermissionRequest, Menu $menu, Permission $permission)
    {
        $permission->update([
            'name' => $menu->name . '-' . $menuPermissionRequest->name
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Permission Updated.',
            'data' => $permission
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @param   Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu, Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Permission Deleted.',
            'data' => $permission
        ]);
    }
}
