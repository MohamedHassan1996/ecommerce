<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Client;

use App\Helpers\ApiResponse;
use App\Http\Requests\Client\ClientAddress\UpdateClientAddressRequest;
use App\Http\Resources\Client\ClientAddress\ClientAddressResource;
use Illuminate\Http\Request;
use App\Models\Client\Client;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
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
            $clientAddresses = $this->clientAddressService->all( $request->clientId);
            return ApiResponse::success(new AllClientAddressCollection(PaginateCollection::paginate( $clientAddresses, $request->pageSize?$request->pageSize:10)));
    }

    public function show($id)
    {
        $clientAddress = $this->clientAddressService->edit($id);
        if ($clientAddress) {
            return ApiResponse::success(new ClientAddressResource($clientAddress));
        }
        return ApiResponse::error(__('curd.not_found'),HttpStatusCode::NOT_FOUND);
    }
    public function store(CreateClientAddressRequest $createClientAddressRequest)
    {
        $data = $createClientAddressRequest->validated();
        $clientAddress = $this->clientAddressService->create($data);
        return ApiResponse::success([], __('curd.created'), HttpStatusCode::CREATED);
    }
    public function update(UpdateClientAddressRequest $updateClientAddressRequest, $id)
    {
        $data = $updateClientAddressRequest->validated();
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

