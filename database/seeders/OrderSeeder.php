<?php

namespace Database\Seeders;

use App\Enums\Order\DiscountType;
use App\Enums\Order\OrderStatus;
use App\Models\Client\Client;
use App\Models\Product\Product;
use App\Services\Order\OrderService;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function run(): void
    {
        $clients = Client::has('phones')->has('emails')->has('addresses')->get();
        $products = Product::where(function ($q) {
            $q->where('is_limited_quantity', false)
              ->orWhere('quantity', '>', 0);
        })->get();

        if ($clients->isEmpty() || $products->isEmpty()) {
            echo "No valid clients or products found in DB.\n";
            return;
        }

        foreach ($clients as $client) {
            $clientPhone = $client->phones()->inRandomOrder()->first();
            $clientEmail = $client->emails()->inRandomOrder()->first();
            $clientAddress = $client->addresses()->inRandomOrder()->first();

            if (!$clientPhone || !$clientEmail || !$clientAddress) {
                continue;
            }

            $orderItems = [];

            $selectedProducts = $products->shuffle()->take(3);
            foreach ($selectedProducts as $product) {
                $qty = rand(1, 5);

                if ($product->is_limited_quantity && $product->quantity < $qty) {
                    continue;
                }

                $orderItems[] = [
                    'productId' => $product->id,
                    'qty' => $qty
                ];
            }

            if (empty($orderItems)) {
                continue;
            }

            $discountType = fake()->randomElement([
                DiscountType::FIXCED->value,
                DiscountType::PERCENTAGE->value,
                DiscountType::NO_DISCOUNT->value,
            ]);
            $discount = $discountType !== DiscountType::NO_DISCOUNT->value ? rand(5, 25) : 0;

            $order = $this->orderService->createOrder([
                'clientId' => $client->id,
                'clientPhoneId' => $clientPhone->id,
                'clientEmailId' => $clientEmail->id,
                'clientAddressId' => $clientAddress->id,
                'discountType' => $discountType,
                'discount' => $discount,
                'status' => OrderStatus::DRAFT->value,
                'orderItems' => $orderItems,
            ]);

            echo "✅ Created order #{$order->id} | Total: {$order->price}, After Discount: {$order->price_after_discount}\n";
        }
    }
}
