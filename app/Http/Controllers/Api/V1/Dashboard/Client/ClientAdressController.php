<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Client;

use App\Enums\ResponseCode\HttpStatusCode;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Services\Client\ClientAddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClientAdressController extends Controller
{
    protected $clientAddressService;
    public function __construct( ClientAddressService $clientAddressService)
    {
        $this->clientAddressService = $clientAddressService;
    }

    public function index(Request $request)
    {
            $clientAddresses = $this->clientAddressService->all( $request->clientId);
            return ApiResponse::success($clientAddresses);
    }

    public function show($id)
    {
        $clientAddress = $this->clientAddressService->edit($id);
        if ($clientAddress) {
            return ApiResponse::success($clientAddress);
        }
        return ApiResponse::error(__('curd.not_found'),HttpStatusCode::NOT_FOUND);
    }
    public function store(Request $request)
    {
       $data= $request->validate([
            'clientId' => 'required|exists:clients,id',
            'address' => 'required|string|unique:addresses,address|max:255',
            'isMain' => 'required|boolean',
        ]);

        $clientAddress = $this->clientAddressService->create($data);
        return ApiResponse::success([], __('curd.created'), HttpStatusCode::CREATED);
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'clientId' => 'required|exists:clients,id',
           'address' => 'required|string|unique:addresses,address|max:255',
            'isMain' => 'required|boolean',
        ]);

        $clientAddress = $this->clientAddressService->update($id, $data);
        if ($clientAddress) {
            return ApiResponse::success([], __('curd.updated'));
        }
        return ApiResponse::error(__('curd.not_found'), HttpStatusCode::NOT_FOUND);
    }
    public function destroy($id)
    {
        $clientAddress = $this->clientAddressService->delete($id);
        if ($clientAddress) {
            return ApiResponse::success([], __('curd.deleted'));
        }
        return ApiResponse::error(__('curd.not_found'), HttpStatusCode::NOT_FOUND);
    }
}

