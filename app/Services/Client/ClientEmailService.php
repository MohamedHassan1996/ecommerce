<?php
namespace App\Services\Client;

use App\Enums\ResponseCode\HttpStatusCode;
use App\Helpers\ApiResponse;
use App\Models\Client\ClientEmail;
use Illuminate\Support\Facades\Http;

class ClientEmailService {
    public function all($clientId)
    {
        $ClientEmail=ClientEmail::where('client_id', $clientId)->get();
        return $ClientEmail;
    }

    public function create($data)
    {
        $clientEmail= ClientEmail::create([
            'client_id' => $data['clientId'],
            'email' => $data['email'],
            'is_main' => $data['isMain'],
        ]);

        return $clientEmail;
    }

    public function edit($id)
    {
        $ClientEmail=ClientEmail::find($id);
        return $ClientEmail;
    }
    public function update(int $id,array $data)
    {
        $ClientEmail=ClientEmail::find($id);
        $ClientEmail->update([
            'client_id' => $data['clientId'],
            'email' => $data['email'],
            'is_main' => $data['isMain'],
        ]);
        return $ClientEmail;
    }
    public function delete($clientId)
    {
        $ClientEmail=ClientEmail::find($clientId);
        $ClientEmail->delete();
        return ApiResponse::success([], __('messages.deleted'),  HttpStatusCode::OK);
    }
}


