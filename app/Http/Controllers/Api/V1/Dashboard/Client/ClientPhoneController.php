<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Client;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Enums\Client\IsMainClient;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Services\Client\ClientPhoneService;

class ClientPhoneController extends Controller
{
     public $clientPhoneService;
     public function __construct(ClientPhoneService $clientPhoneService)
     {
         $this->clientPhoneService = $clientPhoneService;
     }


    public function index()
    {
        $clientPhones = $this->clientPhoneService->all();
        return ApiResponse::success($clientPhones);
    }

    public function show($id)
    {
        $clientPhone = $this->clientPhoneService->edit($id);
        if (!$clientPhone) {
            return apiResponse::error(__('crud.not_found'), HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success($clientPhone);
    }

    public function store(Request $request)
    {
        //client_id , phone , is_main , country_code
        $data=$request->validate([
            'phone' => 'required|string|unique:phones,phone|max:255',
            'clientId' => 'required|integer|exists:clients,id',
            'isMain' =>['required',new Enum(IsMainClient::class)],
            'countryCode' => 'nullable|string|max:255',
        ]);

        $clientPhone = $this->clientPhoneService->create($data);
        return ApiResponse::success([], __('crud.created'),  HttpStatusCode::CREATED);
    }

    public function update(Request $request, $id)
    {
       $data= $request->validate([
            'phone' => 'required|string|unique:phones,phone|max:255',
            'clientId' => 'required|integer|exists:clients,id',
            'isMain' =>['required',new Enum(IsMainClient::class)],
            'countryCode' => 'nullable|string|max:255',
        ]);

        $clientPhone = $this->clientPhoneService->update($id, $data);
        if (!$clientPhone) {
            return apiResponse::error(__('crud.not_found'), HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success([], __('crud.updated'),  HttpStatusCode::OK);
    }

    public function destroy($id)
    {
        $clientPhone = $this->clientPhoneService->delete($id);
        if (!$clientPhone) {
            return apiResponse::error(__('crud.not_found'), HttpStatusCode::NOT_FOUND);
        }
        return  ApiResponse::success([], __('crud.deleted'),  HttpStatusCode::OK);
    }
}
