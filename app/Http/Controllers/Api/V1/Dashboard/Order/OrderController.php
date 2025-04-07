<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Http\Requests\Order\CreateOrderRequest;

class OrderController extends Controller
{
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        // $this->middleware('auth:api');
    }

    public function index()
    {
        return $this->orderService->all();
    }

    public function edit($id)
    {
        return $this->orderService->editOrder($id);
    }

    public function store(CreateOrderRequest $createOrderRequest)
    {

        $data= $createOrderRequest->validated();
        return $this->orderService->createOrder($data);
    }

    public function update(Request $request, $id)
    {
        return $this->orderService->updateOrder($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->orderService->deleteOrder($id);
    }
}
