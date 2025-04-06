<?php
namespace App\Services\Order;

use App\Enums\ResponseCode\HttpStatusCode;
use App\Helpers\ApiResponse;
use App\Models\Order\Order;
use Illuminate\Support\Facades\Http;

class OrderService
{
    protected $orderItemService;
    public function __construct( OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }


    public function all(){
         $orders = Order::get();
            return $orders;
    }
    public function edit($id){
        $order = Order::with(['client','clientEmail','clientPhone','clientAdress'])->find($id);
        return $order;
    }

    public function store(array $data){

        // $priceAfterDiscount = $data['price'] - ($data['price'] * ($data['discount'] / 100));
        $totalPrice = 0;
        $totalPriceAfterDiscount = 0;

        $order = Order::create([
            'discount' => $data['discount']??null,
            'discount_type' => $data['discountType']??null,
            'price' => $totalPrice,
            'price_after_discount' => $totalPrice,
            'client_phone_id' => $data['clientPhoneId'],
            'client_email_id' => $data['clientEmailId'],
            'client_address_id' => $data['clientAddressId'],
            'client_id' => $data['clientId'],
            'status' => $data['status']
      ]);
      foreach ($data['items'] as $itemData) {
        $item= $this->orderItemService->store([
                'orderId' => $order->id,
                ...$itemData
            ]);
            $totalPrice += $item->price * $item->qty;
        }

        $totalPriceAfterDiscount = $totalPrice;
        if ($data['discountType'] == 1) {
            $totalPriceAfterDiscount = $totalPrice - ($totalPrice * ($data['discount'] / 100));
        } elseif ($data['discountType'] === 0) {
            $totalPriceAfterDiscount = $totalPrice - $data['discount'];
        }
        $order->update([
            'price_after_discount' => $totalPriceAfterDiscount,
            'price' => $totalPrice
        ]);

        return $order;

    }
    public function update(int $id,array $data){
        $order = Order::find($id);
        $order->update([
            'number' => $data['number'],
            'discount' => $data['discount'],
            'discount_type' => $data['discount_type'],
            'price_after_discount' => $data['price_after_discount'],
            'client_phone_id' => $data['client_phone_id'],
            'client_email_id' => $data['client_email_id'],
            'client_address_id' => $data['client_address_id'],
            'client_id' => $data['client_id'],
            'status' => $data['status'],
        ]);
        $order->save();
        return $order;

    }
    public function delete($id){
            $order = Order::find($id);
            $order->delete();
    }

}
