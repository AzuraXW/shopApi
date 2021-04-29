<?php

namespace Database\Factories;

use App\Models\Slides;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlidesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slides::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(20),
            'url' => '',
            'img' => 'https://placeimg.com/1920/480/any',
            'status' => 1,
            'seq' => $this->faker->numberBetween([1, 99])
        ];
    }
}
