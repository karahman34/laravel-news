<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = ['publish', 'draft', 'pending'];

        return [
            'banner_image' => 'default.png',
            'title' => preg_replace('/[^ a-zA-Z]+/', '', ucwords($this->faker->sentence(rand(6, 10)))),
            'content' => $this->faker->paragraph(rand(20, 100)),
            'views' => rand(0, 1000),
            'is_headline' => 'N',
            'status' => $status[0]
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (News $news) {
            $tags = Tag::inRandomOrder()->limit(rand(2, 4))->get()->pluck('id')->toArray();

            $news->tags()->sync($tags);
        });
    }
}
