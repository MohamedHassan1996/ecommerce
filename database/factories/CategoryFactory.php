<?php

namespace Database\Factories;

use App\Models\Product\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

// /**
//  * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
//  */
class CategoryFactory extends Factory
{
    protected $model =Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {//name ,path ,is_active, parent_id
        return [
            'name' => fake()->unique()->title(),
            'parent_id' => fake()->numberBetween(1,4),
            'is_active' => fake()->boolean(),
            'path' => 'factory/index.png'
        ];
    }
}
