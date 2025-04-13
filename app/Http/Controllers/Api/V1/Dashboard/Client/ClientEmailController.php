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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientEmailController extends Controller
{
    protected $clientEmailService;
 public function __construct(ClientEmailService $clientEmailService)
 {
     $this->clientEmailService = $clientEmailService;
 }
    public function index(Request $request)
    {

        $ClientEmail = $this->clientEmailService->allClientEmails($request->clientId);
        return ApiResponse::success(new AllClientEmailCollection(PaginateCollection::paginate( $ClientEmail, $request->pageSize?$request->pageSize:10)));
    }
    public function store(CreateClientEmailRequest $createClientEmailRequest)
    {
        try{
            $this->clientEmailService->createClientEmail($createClientEmailRequest->validated());
            return ApiResponse::success([], __('messages.created'), HttpStatusCode::CREATED);
        }catch (\Exception $e) {
            return ApiResponse::error(__('crud.server_error'), [], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }
    public function show($id)
    {
        $ClientEmail = $this->clientEmailService->editClientEmail($id);
        return ApiResponse::success(new ClientEmailResource($ClientEmail));
    }
    public function update($id,UpdateClientEmailRequest $updateClientEmailRequest)
    {
        $ClientEmail = $this->clientEmailService->updateClientEmail($id, $updateClientEmailRequest->validated());
        if(!$ClientEmail){
            return ApiResponse::error( __('messages.not_found'),[], HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success([], __('messages.updated'));
    }
    public function destroy(int $id)
    {
        try{
          $this->clientEmailService->deleteClientEmail($id);
            return ApiResponse::success([], __('messages.deleted'), HttpStatusCode::OK);
        }catch (ModelNotFoundException $th) {
            return ApiResponse::error( __('messages.not_found'),[], HttpStatusCode::NOT_FOUND);
        }catch (\Exception $e) {
            return ApiResponse::error(__('crud.server_error'), [], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }


    }

}
