<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    use HandlesAuthorization;

    public $prefixPermission = 'permissions';

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can($this->prefixPermission . '-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can($this->prefixPermission . '-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return mixed
     */
    public function update(User $user, Permission $permission)
    {
        return $user->can($this->prefixPermission . '-update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return mixed
     */
    public function delete(User $user, Permission $permission)
    {
        return $user->can($this->prefixPermission . '-delete');
    }

    /**
     * Determine whether the user can export the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function export(User $user)
    {
        return $user->can($this->prefixPermission . '-export');
    }

    /**
     * Determine whether the user can import the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function import(User $user)
    {
        return $user->can($this->prefixPermission . '-import');
    }
}
