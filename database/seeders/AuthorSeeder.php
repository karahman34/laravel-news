<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author = User::create([
            'email' => 'author@example.com',
            'name' => 'author',
            'password' => Hash::make('password')
        ]);

        $author->assignRole('author');
    }
}
