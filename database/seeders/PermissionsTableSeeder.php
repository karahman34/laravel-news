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
        $data = ['menus-create', 'menus-update', 'menus-delete', 'menus-view'];

        foreach ($data as $name) {
            Permission::create(['name' => $name]);
        }
    }
}
