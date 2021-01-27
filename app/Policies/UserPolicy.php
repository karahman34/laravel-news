<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public $prefixPermission = 'users';

    /**
     * Get static instance.
     *
     * @return $this
     */
    public static function static()
    {
        $self = new self;
        return $self;
    }

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
     * Determine whether the user can sync the roles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function syncRoles(User $user)
    {
        return $user->can($this->prefixPermission . '-sync_roles');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->can($this->prefixPermission . '-update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $user->can($this->prefixPermission . '-delete');
    }
}
