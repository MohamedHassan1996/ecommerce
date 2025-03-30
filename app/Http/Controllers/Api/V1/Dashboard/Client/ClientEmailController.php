<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Client;

use App\Enums\ResponseCode\HttpStatusCode;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Client\ClientEmailService;
use Illuminate\Http\Request;

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
        return ApiResponse::success($ClientEmail);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'clientId' => 'required|integer',
            'email' => 'required|string',
            'isMain' => 'required|boolean',
        ]);
        $ClientEmail = $this->clientEmailService->create($data);
        return ApiResponse::success([], __('messages.created'), HttpStatusCode::CREATED);
    }
    public function show($id)
    {
        $ClientEmail = $this->clientEmailService->edit($id);
        return ApiResponse::success($ClientEmail);
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'clientId' => 'required|integer',
            'email' => 'required|string',
            'isMain' => 'required|boolean',
        ]);
        $ClientEmail = $this->clientEmailService->update($id, $data);
        if(!$ClientEmail){
            return ApiResponse::error([], __('messages.not_found'), HttpStatusCode::NOT_FOUND);
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
