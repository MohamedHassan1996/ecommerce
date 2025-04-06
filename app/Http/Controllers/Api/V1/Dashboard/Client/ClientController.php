<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Client;

use App\Helpers\ApiResponse;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\Client\ClientResource;
use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Client\ClientService;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Resources\Client\AllClientCollection;
use App\Services\Client\ClientEmailService;
use App\Services\Client\ClientPhoneService;
use App\Services\Client\ClientAddressService;

class ClientController extends Controller
{
    protected $clientService;
    protected $clientPhoneService;
    protected $clientEmailService;
    protected $clientAddressService;
   public function __construct( ClientService $clientService, ClientPhoneService $clientPhoneService, ClientEmailService $clientEmailService, ClientAddressService $clientAddressService)
    {
        $this->clientService = $clientService;
        $this->clientPhoneService = $clientPhoneService;
        $this->clientEmailService = $clientEmailService;
        $this->clientAddressService = $clientAddressService;
    }
    public function index(Request $request)
    {
         $clients = $this->clientService->all();
         return ApiResponse::success(new AllClientCollection(PaginateCollection::paginate($clients, $request->pageSize?$request->pageSize:10)));
    }
    public function show($id)
    {
        $client = $this->clientService->edit($id);
        if (!$client) {
            return apiResponse::error(__('crud.not_found'), HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success(new ClientResource($client));
    }
    public function store(CreateClientRequest $createClientRequest)
    {

        try {
            DB::beginTransaction();
            $data = $createClientRequest->validated();
            $client = $this->clientService->store($data);
            DB::commit();
            return ApiResponse::success([],__('crud.created'),HttpStatusCode::CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::UNPROCESSABLE_ENTITY);
        }


    }
    public function update( $id,UpdateClientRequest $updateClientRequest)
    {
        try {
            $data = $updateClientRequest->validated();
            $client = $this->clientService->update($data, $id);
            return ApiResponse::success([],__('crud.updated'),HttpStatusCode::OK);
        } catch (\Throwable $th) {
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
    }
    public function destroy($id)
    {
        try{
            $client = $this->clientService->destroy($id);
            return ApiResponse::success([],__('crud.deleted'),HttpStatusCode::OK);
        }catch (\Throwable $th) {
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::UNPROCESSABLE_ENTITY);
        }

    }
}
