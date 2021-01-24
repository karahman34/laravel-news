<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'parent_id' => null,
                'icon' => 'fas fa-fire',
                'name' => 'dashboard',
                'path' => '/administrator/dashboard',
                'has_sub_menus' => 'N',
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-compass',
                'name' => 'menus',
                'path' => '/administrator/menus',
                'has_sub_menus' => 'N',
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-users',
                'name' => 'User Management',
                'path' => '/administrator/user-management',
                'has_sub_menus' => 'Y',
                'sub_menus' => [
                    [
                        'name' => 'roles',
                        'path' => '/roles',
                        'has_sub_menus' => 'N',
                    ],
                    [
                        'name' => 'permissions',
                        'path' => '/permissions',
                        'has_sub_menus' => 'N',
                    ],
                ]
            ]
        ];

        // Create menus
        foreach ($data as $menu) {
            $menuModel = Menu::create(collect($menu)->except('sub_menus')->toArray());

            if (isset($menu['sub_menus'])) {
                foreach ($menu['sub_menus'] as $subMenu) {
                    $subMenu['parent_id'] = $menuModel->id;
                    $subMenu['path'] = $menu['path'] . $subMenu['path'];
                    Menu::create($subMenu);
                }
            }
        }
    }
}
