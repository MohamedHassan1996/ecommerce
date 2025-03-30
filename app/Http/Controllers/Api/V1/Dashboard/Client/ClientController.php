<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Client;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Enums\Client\IsMainClient;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use App\Services\Client\ClientService;
use App\Enums\ResponseCode\HttpStatusCode;
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
    public function index()
    {
         $client = $this->clientService->all();
         return ApiResponse::success($client);
    }
    public function show($id)
    {
        $client = $this->clientService->edit($id);
        if (!$client) {
            return apiResponse::error(__('crud.not_found'), HttpStatusCode::NOT_FOUND);
        }
        return ApiResponse::success($client);
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'phones'=>'nullable|array',//phone ,is_main , country_code
                'phones.*.phone'=>'required|unique:phones,phone|max:255',
                'phones.*.isMain'=>['required',new Enum(IsMainClient::class)],
                'phones.*.countryCode'=>'nullable|string|max:255',
                'emails'=>'nullable|array',//email ,is_main
                'emails.*.isMain'=>['required',new Enum(IsMainClient::class)],
                'emails.*.email'=>'required|email|unique:emails,email|max:255',
                'addresses'=>'nullable|array',//address ,is_main
                'addresses.*.address'=>'required|string|unique:addresses,address|max:255',
                'addresses.*.isMain'=>['required',new Enum(IsMainClient::class)],
            ]);
            $client = $this->clientService->store($data);
            DB::commit();
            return ApiResponse::success([],__('crud.created'),HttpStatusCode::CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error(__('crud.server error'),HttpStatusCode::UNPROCESSABLE_ENTITY);
        }


    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $client = $this->clientService->update($data, $id);
        return ApiResponse::success([],__('crud.updated'),HttpStatusCode::OK);
    }
    public function destroy($id)
    {
        $client = $this->clientService->destroy($id);
        return ApiResponse::success([],__('crud.deleted'),HttpStatusCode::OK);
    }
}
