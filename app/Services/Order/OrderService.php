<?php
namespace App\Services\Order;

use App\Enums\Order\DiscountType;
use App\Enums\Order\OrderStatus;
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
    public function allOrders(){
         $orders = Order::get();
            return $orders;
    }
    public function editOrder($id){
        return Order::with(['items'])->find($id);
    }

    public function createOrder(array $data){

        $totalPrice = 0;
        $totalPriceAfterDiscount = 0;

        $order = Order::create([
            'discount' => $data['discount']??null,
            'discount_type' => DiscountType::from($data['discountType'])->value,
            'client_phone_id' => $data['clientPhoneId'],
            'client_email_id' => $data['clientEmailId'],
            'client_address_id' => $data['clientAddressId'],
            'client_id' => $data['clientId'],
            'status' => OrderStatus::from($data['status'])->value,
        ]);

        foreach ($data['orderItems'] as $itemData) {
            $item= $this->orderItemService->createOrderItem([
                    'orderId' => $order->id,
                    ...$itemData
                ]);
            $totalPrice += $item->price * $item->qty;
        }

        if ($order->discount_type == DiscountType::PERCENTAGE) {
            $totalPriceAfterDiscount = $totalPrice - ($totalPrice * ($data['discount'] / 100));
        } elseif ($order->discount_type == DiscountType::FIXCED) {
            $totalPriceAfterDiscount = $totalPrice - $data['discount'];
        }elseif($order->discount_type == DiscountType::NO_DISCOUNT){
            $totalPriceAfterDiscount = $totalPrice;
        }
        $order->update([
            'price_after_discount' => $totalPriceAfterDiscount,
            'price' => $totalPrice
        ]);

        return $order;

    }
    public function updateOrder(int $id,array $data){
        $order = Order::find($id);
        $order->discount = $data['discount']??null;
        $order->discount_type = DiscountType::from($data['discountType'])->value;
        $order->client_phone_id = $data['clientPhoneId']??null;
        $order->client_email_id = $data['clientEmailId']??null;
        $order->client_address_id = $data['clientAddressId']??null;
        $order->client_id = $data['clientId'];
        $order->status = OrderStatus::from($data['status'])->value;
        $order->save();

        $totalPrice = 0;
        $totalPriceAfterDiscount = 0;
        foreach ($data['orderItems'] as $itemData) {
            if($itemData['actionStatus'] ==='update'){
                $item= $this->orderItemService->updateOrderItem($itemData['orderItemId'],[
                    'orderId' => $order->id,
                    ...$itemData
                ]);
                $totalPrice += $item->price * $item->qty;
                // dd($itemData);
            }
            if($itemData['actionStatus'] ==='delete'){
                $this->orderItemService->deleteOrderItem($itemData['orderItemId']);
            }
            if($itemData['actionStatus'] ==='create'){
                    $item= $this->orderItemService->createOrderItem([
                        'orderId' => $order->id,
                        ...$itemData
                    ]);
                    $totalPrice += $item->price * $item->qty;
            }
            if($itemData['actionStatus'] ===''){
                $item= $this->orderItemService->editOrderItem($itemData['orderItemId']);
                $totalPrice += $item->price * $item->qty;
            }
        }
        if ($order->discount_type == DiscountType::PERCENTAGE) {
            $totalPriceAfterDiscount = $totalPrice - ($totalPrice * ($data['discount'] / 100));
        } elseif ($order->discount_type == DiscountType::FIXCED) {
            $totalPriceAfterDiscount = $totalPrice - $data['discount'];
        }elseif($order->discount_type == DiscountType::NO_DISCOUNT){
            $totalPriceAfterDiscount = $totalPrice;
        }
        $order->price_after_discount = $totalPriceAfterDiscount;
        $order->price = $totalPrice;
        $order->save();
        return $order;

    }
    public function deleteOrder(int $id){
            $order = Order::find($id);
            $order->delete();
    }

}
