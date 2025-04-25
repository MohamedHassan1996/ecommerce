<?php

namespace App\Http\Controllers\Api\V1\Website\Order;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Client\Client;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ClientOrderController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('client:api'),
        ];
    }

    public function index(Request $request)
    {
     $clientId=$request->user()->client_id;
     $clientOrder = Client::with('orders')->where('id',$clientId)->first();
     return ApiResponse::success($clientOrder);
    }
    public function show()
    {

    }
}
