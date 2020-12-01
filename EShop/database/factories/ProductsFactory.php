<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'brand' => $this->faker->name,
            'describe' => $this->faker->text,
            'price' => $this->faker->numberBetween(1000, 10000),
            'image' => $this->faker->image(),
        ];
    }
}
