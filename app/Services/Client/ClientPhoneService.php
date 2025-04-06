<?php
namespace App\Services\Client;

use App\Enums\ResponseCode\HttpStatusCode;
use App\Helpers\ApiResponse;
use App\Models\Client\Client;
use App\Models\Client\ClientPhone;
use Illuminate\Support\Facades\Http;

class ClientPhoneService
{

   public function all()
   {
     $Phones=ClientPhone::get();
        return $Phones;

   }
   public function edit(int $id)
   {
      $client =ClientPhone::find($id);
      if(!$client){
         return ApiResponse::success([],__('crud.not_found'));
      }
      return $client;

   }
    public function create(array $data)
    {
        $client = ClientPhone::create([
            'client_id' => $data['clientId'],
            'phone' => $data['phone'],
            'is_main' => $data['isMain'],
            'country_code' => $data['countryCode'] ?? null,
        ]);
        return $client;
    }

    public function update($id, array $data)
    {
        $client = ClientPhone::find($id);
        if (!$client) {
            return ApiResponse::success([], __('crud.not_found'),HttpStatusCode::NOT_FOUND);
        }
        $client->update([
            'client_id' => $data['clientId'],
            'phone' => $data['phone'],
            'is_main' => $data['isMain'],
            'country_code' => $data['countryCode'] ?? null,
        ]);
        return $client;
    }

    public function delete($id)
    {
        $clientPhone = ClientPhone::find($id);
        if (!$clientPhone) {
            return ApiResponse::success([], __('crud.not_found'), HttpStatusCode::NOT_FOUND);
        }
        $clientPhone->delete();
        return ApiResponse::success([], __('crud.deleted'));
    }

}
