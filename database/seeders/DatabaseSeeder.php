<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TagsTableSeeder::class);
        $this->call(RolesTableSeeder::class);

        \App\Models\User::factory(10)
                            ->has(News::factory()->count(rand(1, 5)))
                            ->create();

        $this->call(MenusTableSeeder::class);
        $this->call(SuperAdminSeeder::class);
        $this->call(AuthorSeeder::class);
        $this->call(HeadlineNewsSeeder::class);
    }
}
