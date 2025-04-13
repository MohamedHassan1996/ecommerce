<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Client;

use App\Helpers\ApiResponse;
use App\Http\Requests\Client\ClientAddress\UpdateClientAddressRequest;
use App\Http\Resources\Client\ClientAddress\ClientAddressResource;
use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Http\Requests\Client\ClientAddress\CreateClientAddressRequest;
use App\Services\Client\ClientAddressService;
use App\Http\Resources\Client\ClientAddress\AllClientAddressCollection;

class ClientAdressController extends Controller
{
    protected $clientAddressService;
    public function __construct( ClientAddressService $clientAddressService)
    {
        $this->clientAddressService = $clientAddressService;
    }

    public function index(Request $request)
    {
            $clientAddresses = $this->clientAddressService->allClientAddress( $request->clientId);
            return ApiResponse::success(new AllClientAddressCollection(PaginateCollection::paginate( $clientAddresses, $request->pageSize?$request->pageSize:10)));
    }

    public function show(int $id)
    {
        $clientAddress = $this->clientAddressService->editClientAddress($id);
        if (!$clientAddress) {
            return ApiResponse::error(__('curd.not_found'),HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success(new ClientAddressResource($clientAddress));
    }
    public function store(CreateClientAddressRequest $createClientAddressRequest)
    {
        $this->clientAddressService->createClientAddress($createClientAddressRequest->validated());
        return ApiResponse::success([], __('curd.created'), HttpStatusCode::CREATED);
    }
    public function update(int $id,UpdateClientAddressRequest $updateClientAddressRequest)
    {
        $clientAddress = $this->clientAddressService->updateClientAddress($id, $updateClientAddressRequest->validated());
        if (!$clientAddress) {
            return ApiResponse::error(__('curd.not_found'), HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success([], __('curd.updated'));
    }
    public function destroy(int $id)
    {
        $this->clientAddressService->deleteClientAddress($id);
        return ApiResponse::success([], __('curd.deleted'));
    }
}

