<?php

namespace Database\Seeders\Category;

use App\Models\Product\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $model = Category::class;
    public function run()
    {
        Category::factory()->count(20)->create();
    }
}
