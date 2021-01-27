<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenusTableSeeder extends Seeder
{
    private function createPermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    private function createSubMenus(Menu $parentMenu, array $subMenus)
    {
        foreach ($subMenus as $subMenu) {
            $subMenu['parent_id'] = $parentMenu->id;
            $subMenu['path'] = $parentMenu->path . $subMenu['path'];

            Menu::create(collect($subMenu)->except('permissions')->toArray());

            // Create permissions.
            if (isset($subMenu['permissions'])) {
                $this->createPermissions($subMenu['permissions']);
            }
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'parent_id' => null,
                'icon' => 'fas fa-fire',
                'name' => 'dashboard',
                'path' => '/administrator/dashboard',
                'has_sub_menus' => 'N',
                'permissions' => [],
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-compass',
                'name' => 'menus',
                'path' => '/administrator/menus',
                'has_sub_menus' => 'N',
                'permissions' => ['menus-create', 'menus-update', 'menus-delete', 'menus-view', 'menus-sync_permissions'],
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-tags',
                'name' => 'tags',
                'path' => '/administrator/tags',
                'has_sub_menus' => 'N',
                'permissions' => ['tags-create', 'tags-update', 'tags-delete', 'tags-view'],
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-newspaper',
                'name' => 'news',
                'path' => '/administrator/news',
                'has_sub_menus' => 'N',
                'permissions' => ['news-create', 'news-update', 'news-delete', 'news-view'],
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-users',
                'name' => 'users',
                'path' => '/administrator/users',
                'has_sub_menus' => 'N',
                'permissions' => ['users-create', 'users-update', 'users-delete', 'users-view', 'users-sync_roles'],
            ],
            [
                'parent_id' => null,
                'icon' => 'fas fa-users',
                'name' => 'User Managements',
                'path' => '/administrator/user-managements',
                'has_sub_menus' => 'Y',
                'sub_menus' => [
                    [
                        'name' => 'roles',
                        'path' => '/roles',
                        'has_sub_menus' => 'N',
                        'permissions' => ['roles-create', 'roles-update', 'roles-delete', 'roles-view', 'roles-sync_permissions'],
                    ],
                    [
                        'name' => 'permissions',
                        'path' => '/permissions',
                        'has_sub_menus' => 'N',
                        'permissions' => ['permissions-create', 'permissions-update', 'permissions-delete', 'permissions-view'],
                    ],
                ]
            ]
        ];

        // Create menus
        foreach ($menus as $menu) {
            $menuModel = Menu::create(collect($menu)->except(['sub_menus', 'permissions'])->toArray());

            // Create permissions
            if (isset($menu['permissions']) && count($menu['permissions']) > 0) {
                $this->createPermissions($menu['permissions']);
            }

            // Create sub menus
            if (isset($menu['sub_menus'])) {
                $this->createSubMenus($menuModel, $menu['sub_menus']);
            }
        }
    }
}
