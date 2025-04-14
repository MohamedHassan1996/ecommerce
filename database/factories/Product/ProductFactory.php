<?php

namespace Database\Factories\Product;

use App\Enums\Product\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\Product>
 */
class ProductFactory extends Factory
{
    protected $model = \App\Models\Product\Product::class;
    /**
     * Define the model's default state.
     *
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 50, 1000),
            'status' => $this->faker->randomElement([ProductStatus::ACTIVE->value, ProductStatus::INACTIVE->value])
        ];
    }
}
