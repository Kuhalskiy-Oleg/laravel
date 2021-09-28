<?php

namespace Database\Factories;

use App\Models\SelectedCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SelectedCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SelectedCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_images_id' => $this->faker->numberBetween($min = 1, $max = 10),
            'subscribers_id' => $this->faker->numberBetween($min = 1, $max = 1000),
        ];
    }
}
