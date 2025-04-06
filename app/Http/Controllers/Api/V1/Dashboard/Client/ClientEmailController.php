<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Client;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Services\Client\ClientEmailService;
use App\Http\Resources\Client\ClientEmails\ClientEmailResource;
use App\Http\Requests\Client\ClientEmail\CreateClientEmailRequest;
use App\Http\Requests\Client\ClientEmail\UpdateClientEmailRequest;
use App\Http\Resources\Client\ClientEmails\AllClientEmailCollection;

class ClientEmailController extends Controller
{
    protected $clientEmailService;
 public function __construct(ClientEmailService $clientEmailService)
 {
     $this->clientEmailService = $clientEmailService;
 }
    public function index(Request $request)
    {

        $ClientEmail = $this->clientEmailService->all($request->clientId);
        return ApiResponse::success(new AllClientEmailCollection(PaginateCollection::paginate( $ClientEmail, $request->pageSize?$request->pageSize:10)));
    }
    public function store(CreateClientEmailRequest $createClientEmailRequest)
    {
        $data = $createClientEmailRequest->validated();
        $ClientEmail = $this->clientEmailService->create($data);
        return ApiResponse::success([], __('messages.created'), HttpStatusCode::CREATED);
    }
    public function show($id)
    {
        $ClientEmail = $this->clientEmailService->edit($id);
        return ApiResponse::success(new ClientEmailResource($ClientEmail));
    }
    public function update(UpdateClientEmailRequest $updateClientEmailRequest, $id)
    {
        $data = $updateClientEmailRequest->validated();
        $ClientEmail = $this->clientEmailService->update($id, $data);
        if(!$ClientEmail){
            return ApiResponse::error( __('messages.not_found'),[], HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success([], __('messages.updated'), HttpStatusCode::OK);
    }
    public function destroy($id)
    {
        $ClientEmail = $this->clientEmailService->delete($id);
        if(!$ClientEmail){
            return ApiResponse::error( __('messages.not_found'), HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success([], __('messages.deleted'), HttpStatusCode::OK);
    }

}
