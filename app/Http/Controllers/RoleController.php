<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->wantsJson()) {
            return view('pages.user-managements.roles', [
                'title' => 'Roles'
            ]);
        }

        return DataTables::of(Role::query())
                            ->addColumn('actions', function (Role $role) {
                                $permissionsButton = '<a href="'.route('administrator.user-managements.roles.show', ['role' => $role]).'" class="btn btn-info btn-modal-trigger" data-modal="#role-permissions-modal"><i class="fas fa-lock"></i></a>';

                                $editButton = '<a href="'.route('administrator.user-managements.roles.edit', ['role' => $role]).'" class="btn btn-warning btn-modal-trigger" data-modal="#form-role-modal"><i class="fas fa-edit"></i></a>';
                                
                                $deleteButton = '<a href="'.route('administrator.user-managements.roles.destroy', ['role' => $role]).'" class="btn btn-danger delete-prompt-trigger has-datatable" data-datatable="#roles-datatable" data-item-name="'.$role->name.'"><i class="fas fa-trash"></i></a>';

                                return $permissionsButton . $editButton . $deleteButton;
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
        return view('components.role.form-modal');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RoleRequest  $roleRequest
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $roleRequest)
    {
        $role = Role::create($roleRequest->only('name'));

        return response()->json([
            'ok' => true,
            'message' => 'Role created.',
            'data' => $role
        ], 201);
    }

    /**
     * Display role's permissions.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return view('components.role.permissions-modal', [
            'role' => $role,
            'rolePermissions' => $role->permissions,
            'permissions' => Permission::all()
        ]);
    }

    /**
     * Sync Role's Permissions.
     *
     * @param   Request  $request
     * @param   Role     $role
     *
     * @return  \Illuminate\Http\Response
     */
    public function syncPermissions(Request $request, Role $role)
    {
        $payload = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|max:255',
        ]);

        if (!isset($payload['permissions'])) {
            $role->syncPermissions([]);
        } else {
            $role->syncPermissions($payload['permissions']);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Success to synchronize role\'s permissions.',
            'data' => $role->permissions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('components.role.form-modal', [
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleRequest  $roleRequest
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $roleRequest, Role $role)
    {
        $role->update($roleRequest->only('name'));

        return response()->json([
            'ok' => true,
            'message' => 'Role updated.',
            'data' => $role
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Role deleted.',
            'data' => $role
        ]);
    }
}
