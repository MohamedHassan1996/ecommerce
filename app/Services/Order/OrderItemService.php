<?php
namespace App\Services\Order;

use App\Models\Order\OrderItem;
use App\Models\Product\Product;

class OrderItemService
{
    public function all()
    {
        $orderItems = OrderItem::get();
        return $orderItems;
    }

    public function editOrderItem($id)
    {
        $orderItem = OrderItem::with(['order', 'product'])->find($id);
        return $orderItem;
    }

    public function createOrderItem(array $data)
    {
        $productPrice = Product::where('id', $data['productId'])->pluck('price')->first();
        $orderItem = OrderItem::create([
            'order_id' => $data['orderId'],
            'product_id' => $data['productId'],
            'price' => $productPrice,
            'qty' => $data['qty'],
        ]);
        return $orderItem;
    }
    public function updateOrderItem(int $id,array $data ){
        $orderItem = OrderItem::find($id);
        if ($orderItem) {
            $orderItem->update([
                'qty' => $data['qty'],
            ]);
            return $orderItem;
        }
        return null;
    }
    public function deleteOrderItem($id)
    {
        $orderItem = OrderItem::find($id);
        if ($orderItem) {
            $orderItem->delete();
            return $orderItem;
        }
        return null;
    }
}
