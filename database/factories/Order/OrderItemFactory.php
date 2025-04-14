<?php

namespace Database\Factories\Order;

use App\Models\Order\Order;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::factory()->create();
        return [
            'order_id' => Order::factory(),
            'product_id' =>$product->id,
            'price' => $product->price,
            'qty' => $this->faker->numberBetween(1, 10),
        ];
    }
}
