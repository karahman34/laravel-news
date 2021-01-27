<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'email' => 'author@example.com',
            'name' => 'author',
            'password' => Hash::make('password')
        ]);

        $role = Role::where('name', 'author')->first();

        $permissions = [
            'news-create', 'news-update', 'news-delete', 'news-view',
            'tags-create', 'tags-update', 'tags-delete', 'tags-view'
        ];
        
        $role->syncPermissions($permissions);

        $user->assignRole($role->name);
    }
}
