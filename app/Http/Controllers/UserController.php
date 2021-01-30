<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\UserRequest;
use App\Imports\UsersImport;
use App\Models\User;
use App\Traits\ExcelTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    use ExcelTrait;

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        if (!$request->wantsJson()) {
            return view('pages.administrator.users', [
                'title' => 'Users'
            ]);
        }

        $auth = $request->user();
        $user = User::query();
        $user->with(['roles:id,name']);

        return DataTables::of(User::query())
                            ->addColumn('roles', function (User $user) {
                                return $user->roles->implode('name', ',');
                            })
                            ->addColumn('actions', function (User $user) use ($auth) {
                                $syncRoles = '';
                                $editButton = '';
                                $deleteButton = '';

                                if ($auth->can('syncRoles', $user)) {
                                    $syncRoles = '<a href="'.route('administrator.users.sync_roles', ['user' => $user]).'" class="btn btn-info btn-modal-trigger" data-modal="#sync-user-roles-modal" title="Sync Roles"><i class="fas fa-lock"></i></a>';
                                }

                                if ($auth->can('update', $user)) {
                                    $editButton = '<a href="'.route('administrator.users.edit', ['user' => $user]).'" class="btn btn-warning btn-modal-trigger" data-modal="#user-form-modal"><i class="fas fa-edit"></i></a>';
                                }
                                
                                if ($auth->can('delete', $user)) {
                                    $deleteButton = '<a href="'.route('administrator.users.destroy', ['user' => $user]).'" class="btn btn-danger delete-prompt-trigger has-datatable" data-datatable="#users-datatable" data-item-name="'.$user->name.'"><i class="fas fa-trash"></i></a>';
                                }

                                return $syncRoles . $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
    * Export Users data.
    *
    * @param   Request  $request
    *
    * @return  mixed
    */
    public function export(Request $request)
    {
        $this->authorize('export', User::class);

        $allowedFormats = ['xlsx', 'csv'];

        if ($request->get('export') != 1) {
            return view('components.export-modal', [
                'action' => route('administrator.users.export'),
                'formats' => $allowedFormats,
            ]);
        }

        return $this->exportFile($request, new UsersExport($request->take), 'users', $allowedFormats);
    }

    /**
     * Import Users data.
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function import(Request $request)
    {
        $this->authorize('import', User::class);

        if ($request->method() === 'GET') {
            return view('components.import-modal', [
                'action' => route('administrator.users.import'),
                'dataTable' => '#users-datatable',
            ]);
        }

        $allowedFormats = ['xlsx', 'csv'];

        return $this->importFile($request, new UsersImport, $allowedFormats);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('components.user.form-modal');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $userRequest
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $userRequest)
    {
        $payload = $userRequest->only(['email', 'name', 'password']);
        $payload['password'] = Hash::make($payload['password']);

        $user = User::create($payload);

        return response()->json([
            'ok' => true,
            'message' => 'User created.',
            'data' => $user
        ], 201);
    }

    /**
     * Sync User Roles.
     *
     * @param   Request  $request
     * @param   User     $user
     * @return  \Illuminate\Http\Response
     */
    public function syncRoles(Request $request, User $user)
    {
        $this->authorize("syncRoles", User::class);

        if (strtolower($request->method()) === 'get') {
            return view('components.user.sync-roles-modal', [
                'user' => $user,
                'userRoles' => $user->roles,
                'roles' => Role::all(),
            ]);
        }

        $payload = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'string|max:255'
        ]);

        if (!isset($payload['roles']) || count($payload['roles']) === 0) {
            $user->syncRoles([]);
        } else {
            $user->syncRoles($payload['roles']);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Success to sync user roles.',
            'data' => $user->roles
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize("update", $user);

        return view('components.user.form-modal', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $userRequest
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $userRequest, User $user)
    {
        $payload = $userRequest->only(['email', 'name']);

        if ($userRequest->has('password')) {
            $payload['password'] = Hash::make($userRequest->password);
        }

        $user->update($payload);
        
        return response()->json([
            'ok' => true,
            'message' => 'User updated.',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize("delete", $user);

        $user->delete();
        
        return response()->json([
            'ok' => true,
            'message' => 'User deleted.',
            'data' => $user
        ]);
    }
}
