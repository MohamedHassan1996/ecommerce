<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Client;

use App\Helpers\ApiResponse;
use App\Http\Resources\Client\ClientContact\ClientContactResource;
use Illuminate\Http\Request;

use App\Utils\PaginateCollection;
use App\Enums\Client\IsMainClient;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Http\Requests\Client\ClientContact\CreateClientContactRequest;
use App\Http\Resources\Client\ClientContact\AllClientContactCollection;
use App\Services\Client\ClientPhoneService;
use App\Http\Resources\Client\ClientContact\AllClientContactResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientPhoneController extends Controller
{
     public $clientPhoneService;
     public function __construct(ClientPhoneService $clientPhoneService)
     {
         $this->clientPhoneService = $clientPhoneService;
     }


    public function index(Request  $request)
    {
        $clientPhones = $this->clientPhoneService->all();
        return ApiResponse::success(new AllClientContactCollection(PaginateCollection::paginate($clientPhones, $request->pageSize?$request->pageSize:10)));
    }

    public function show($id)
    {
        $clientPhone = $this->clientPhoneService->edit($id);
        if (!$clientPhone) {
            return apiResponse::error(__('crud.not_found'), HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success(new ClientContactResource($clientPhone));
    }

    public function store(CreateClientContactRequest $createClientContactRequest)
    {
        try{
            $data = $createClientContactRequest->validated();
            $clientPhone = $this->clientPhoneService->create($data);
            return ApiResponse::success([], __('crud.created'),  HttpStatusCode::CREATED);
        }catch(\Throwable $th){
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
    }

    public function update($id,CreateClientContactRequest $createClientContactRequest )
    {
        try{
            $data=$createClientContactRequest->validated();
            $clientPhone = $this->clientPhoneService->update($id, $data);
            return ApiResponse::success([], __('crud.updated'),  HttpStatusCode::OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error(__('crud.not_found'), [], HttpStatusCode::NOT_FOUND);
        }
        catch(\Throwable $th){
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::UNPROCESSABLE_ENTITY);
        }

    }

    public function destroy($id)
    {
        try{
            $clientPhone = $this->clientPhoneService->delete($id);
            return  ApiResponse::success([], __('crud.deleted'),  HttpStatusCode::OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error(__('crud.not_found'), [], HttpStatusCode::NOT_FOUND);
        }catch(\Throwable $th){
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
    }
}
