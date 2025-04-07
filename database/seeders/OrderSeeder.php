<?php

namespace Database\Seeders;

use App\Models\Order\Order;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Order::factory()->count(10)->create();
        //    ->each(function ($order) {
        //         $order->items()->saveMany(\App\Models\Order\OrderItem::factory(5)->make());
        //     });
    }
}
