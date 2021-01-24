<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'administrator',
            'email' => 'administrator@example.com',
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);

        $role = Role::create([
            'name' => 'super admin'
        ]);

        $user->assignRole($role->name);
    }
}
