<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public $prefixPermission = 'menus';

    /**
     * Get static instance.
     *
     * @return self
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
     * Determine whether the user can sync permissions models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function syncPermissions(User $user)
    {
        return $user->can($this->prefixPermission . '-sync_permissions');
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
     * @param  \App\Models\Menu  $menu
     * @return mixed
     */
    public function update(User $user, Menu $menu)
    {
        return $user->can($this->prefixPermission . '-update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return mixed
     */
    public function delete(User $user, Menu $menu)
    {
        return $user->can($this->prefixPermission . '-delete');
    }
}
