<?php

namespace Database\Seeders;

use App\Models\Product\Product;
use Illuminate\Database\Seeder;
use App\Models\Product\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::factory()->count(10)->create();
        $categories = Category::all();
        foreach ($products as $product) {
            $product->categorys()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        }

    }
}
