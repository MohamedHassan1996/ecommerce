<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Order;

use App\Helpers\ApiResponse;
use App\Http\Resources\Order\AllOrderCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Enums\ResponseCode\HttpStatusCode;
use function PHPUnit\Framework\returnValue;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Utils\PaginateCollection;

class OrderController extends Controller
{
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        // $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $orders= $this->orderService->all();
        return ApiResponse::success(new AllOrderCollection(PaginateCollection::paginate($orders,$request->pageSize?$request->pageSize:10)));
    }

    public function show($id)
    {
        $order=$this->orderService->editOrder($id);
        if(!$order){
            return ApiResponse::success([],__('crud.not_found'),HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success(new OrderResource($order));
    }

    public function store(CreateOrderRequest $createOrderRequest)
    {

        $data= $createOrderRequest->validated();
        $order = $this->orderService->createOrder($data);
        if(!$order){
            return ApiResponse::success([],__('crud.not_found'),HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success([],__('crud.created'),HttpStatusCode::CREATED);
    }

    public function update(Request $request, $id)
    {
        $order= $this->orderService->updateOrder($id, $request->all());
        if(!$order){
            return ApiResponse::success([],__('crud.not_found'),HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success([],__('crud.updated'));
    }

    public function destroy($id)
    {
        $order = $this->orderService->deleteOrder($id);
        if(!$order){
            return ApiResponse::success([],__('crud.not_found'),HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success([],__('crud.deleted'));
    }
}
