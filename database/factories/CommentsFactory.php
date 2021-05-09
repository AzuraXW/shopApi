<?php

namespace Database\Factories;

use App\Models\comments;
use App\Models\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = comments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 2,
            'goods_id' => $this->faker->randomElement(Goods::pluck('id')),
            'rate' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'content' => $this->faker->text(50),
            'star' => $this->faker->randomElement([1, 2, 3, 4, 5])
        ];
    }
}
