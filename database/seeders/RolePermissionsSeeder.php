<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'author')->first();
        if ($role) {
            $permissions = [
                'news-create', 'news-update', 'news-delete', 'news-view',
                'tags-create', 'tags-update', 'tags-delete', 'tags-view'
            ];
            
            $role->syncPermissions($permissions);
        }
    }
}
