<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'sport', 'drama', 'technology',
            'school', 'celebrity', 'models',
            'metro', 'jabar', 'bet', 'food',
            'destination', 'shopping', 'ticket',
            'travel', 'business', 'news', 'community',
            'lifestyle'
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'views' => rand(0, 1000),
            ]);
        }
    }
}
