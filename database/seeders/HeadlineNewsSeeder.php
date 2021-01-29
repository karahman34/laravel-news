<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class HeadlineNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $news = News::inRandomOrder()->limit(4)->get();
        $news->each(function (News $item) {
            $item->update([
                'is_headline' => 'Y'
            ]);
        });
    }
}
