<?php

namespace Database\Factories\Order;

use App\Models\Order\Order;
use App\Models\Client\Client;
use App\Models\Order\OrderItem;
use App\Enums\Order\DiscountType;
use App\Models\Client\ClientEmail;
use App\Models\Client\ClientPhone;
use App\Models\Client\ClientAdrress;
use Database\Factories\Client\ClientAddressFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
// resolve order and order item
//seeder and factory

    public function definition(): array
    {
        $client = Client::factory()->create();
        $clientPhone = ClientPhone::factory()->create(['client_id' => $client->id]);
        $clientEmail = ClientEmail::factory()->create(['client_id' => $client->id]);
        $clientAddress = ClientAdrress::factory()->create(['client_id' => $client->id]);

        // $orderItems=OrderItem::factory()->count(3)->create([
        //     'order_id' =>Order::factory(),
        // ]);
        // $totalPrice = $orderItems->sum(function ($orderItem) {
        //     return $orderItem->price * $orderItem->qty;
        // });
        
        $totalPrice =20000;
        $discountType = $this->faker->randomElement([
            DiscountType::FIXCED,
            DiscountType::PERCENTAGE,
            DiscountType::NO_DISCOUNT
        ]);

        $discount = 0;
        $totalPriceAfterDiscount= $totalPrice;
        if ($discountType === DiscountType::FIXCED) {
            $discount = $this->faker->randomElement([10 , 20]);
            $totalPriceAfterDiscount = $totalPrice - $discount;
        } elseif ($discountType === DiscountType::PERCENTAGE) {
            $discount = $this->faker->randomElement([10 , 20]); // خصم بنسبة مئوية بين 5% و 20%
            $totalPriceAfterDiscount = $totalPrice - (($discount / 100) * $totalPrice); // تطبيق الخصم
        }
        return [
            'number' => 'ORD'.'_'.rand(1000,9999).date('m' ).date('y'),
            'client_id' =>$client->id,
            'client_phone_id' =>$clientPhone->id,
            'client_email_id' => $clientEmail->id,
            'client_address_id' =>$clientAddress->id,
            'status' => $this->faker->boolean(),
            'discount_type' =>$discountType,
            'discount' => $discount,
            'price' => $totalPrice,
            'price_after_discount' => $totalPriceAfterDiscount,
        ];
    }
}
