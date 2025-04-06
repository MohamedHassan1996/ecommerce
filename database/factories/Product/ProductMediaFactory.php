<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use App\Enums\Images\MediaTypeEnum;
use App\Enums\Images\IsMainMediaEnum;
use App\Models\Product\ProductMedia;
use Illuminate\Database\Eloquent\Factories\Factory;


class ProductMediaFactory extends Factory
{
    protected $model =ProductMedia::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => 'factory/book.png',
            'media_type' => $this->faker->randomElement([MediaTypeEnum::IMAGE->value, MediaTypeEnum::VIDEO->value]),
            'is_main' => $this->faker->randomElement([IsMainMediaEnum::ISMAIN->value, IsMainMediaEnum::ISNOTMAIN->value]),
            'product_id' => Product::factory(),
        ];
    }
}
