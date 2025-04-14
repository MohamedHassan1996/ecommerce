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

class ClientController extends Controller
{
    protected $clientService;
   public function __construct( ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
    public function index(Request $request)
    {
         $clients = $this->clientService->allClients();
        //  dd($clients->count());
         return ApiResponse::success(new AllClientCollection($clients));
    }
    public function show(int $id)
    {
        $client = $this->clientService->editClient($id);
        if (!$client) {
            return apiResponse::error(__('crud.not_found'),[], HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success(new ClientResource($client));
    }
    public function store(CreateClientRequest $createClientRequest)
    {

        try {
            DB::beginTransaction();
            $this->clientService->createClient($createClientRequest->validated());
            DB::commit();
            return ApiResponse::success([],__('crud.created'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    public function update(int $id,UpdateClientRequest $updateClientRequest)
    {
        try {
            $this->clientService->updateClient($id,$updateClientRequest->validated());
            return ApiResponse::success([],__('crud.updated'));
        } catch (\Throwable $th) {
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    public function destroy(int $id)
    {
        try{
            $this->clientService->deleteClient($id);
            return ApiResponse::success([],__('crud.deleted'));
        }catch (\Throwable $th) {
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }
}
