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
        $randomCategories = $categories->random(rand(1, 3))->pluck('id')->toArray();
        foreach ($products as $product) {
            if ($categories->count() > 0) {
                $randomCategories = $categories->random(min(rand(1, 3), $categories->count()))->pluck('id')->toArray();
                $product->categories()->attach($randomCategories);
            } 
        }

    }
}
