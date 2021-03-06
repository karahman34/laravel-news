<?php

namespace App\Http\Controllers;

use App\Exports\RolesExport;
use App\Http\Requests\RoleRequest;
use App\Imports\RolesImport;
use App\Traits\ExcelTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    use ExcelTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Role::class);

        if (!$request->wantsJson()) {
            return view('pages.administrator.user-managements.roles', [
                'title' => 'Roles'
            ]);
        }

        $auth = $request->user();

        return DataTables::of(Role::query())
                            ->addColumn('actions', function (Role $role) use ($auth) {
                                $permissionsButton = '';
                                $editButton = '';
                                $deleteButton = '';

                                if ($auth->can('syncPermissions', $role)) {
                                    $permissionsButton = '<a href="'.route('administrator.user-managements.roles.show', ['role' => $role]).'" class="btn btn-info btn-modal-trigger" data-modal="#role-permissions-modal"><i class="fas fa-lock"></i></a>';
                                }

                                if ($auth->can('update', $role)) {
                                    $editButton = '<a href="'.route('administrator.user-managements.roles.edit', ['role' => $role]).'" class="btn btn-warning btn-modal-trigger" data-modal="#form-role-modal"><i class="fas fa-edit"></i></a>';
                                }
                                
                                if ($auth->can('delete', $role)) {
                                    $deleteButton = '<a href="'.route('administrator.user-managements.roles.destroy', ['role' => $role]).'" class="btn btn-danger delete-prompt-trigger has-datatable" data-datatable="#roles-datatable" data-item-name="'.$role->name.'"><i class="fas fa-trash"></i></a>';
                                }

                                return $permissionsButton . $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
    * Export Roles data.
    *
    * @param   Request  $request
    *
    * @return  mixed
    */
    public function export(Request $request)
    {
        $this->authorize('export', Role::class);

        $allowedFormats = ['xlsx', 'csv'];

        if ($request->get('export') != 1) {
            return view('components.export-modal', [
                'action' => route('administrator.user-managements.roles.export'),
                'formats' => $allowedFormats,
            ]);
        }

        return $this->exportFile($request, new RolesExport($request->take), 'roles', $allowedFormats);
    }

    /**
     * Import Roles data.
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function import(Request $request)
    {
        $this->authorize('import', Role::class);

        if ($request->method() === 'GET') {
            return view('components.import-modal', [
                'action' => route('administrator.user-managements.roles.import'),
                'dataTable' => '#roles-datatable',
            ]);
        }

        $allowedFormats = ['xlsx', 'csv'];

        return $this->importFile($request, new RolesImport, $allowedFormats);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Role::class);

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
        $this->authorize('syncPermissions', Role::class);

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
        $this->authorize('syncPermissions', Role::class);

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
        $this->authorize('update', $role);
        
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
        $this->authorize('update', $role);

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
        $this->authorize('delete', $role);

        $role->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Role deleted.',
            'data' => $role
        ]);
    }
}
