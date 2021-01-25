<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['menu-create', 'menu-update', 'menu-delete', 'menu-view'];

        foreach ($data as $name) {
            Permission::create(['name' => $name]);
        }
    }
}
