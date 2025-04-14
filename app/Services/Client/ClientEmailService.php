<?php
namespace App\Services\Client;

use App\Enums\IsMain;
use App\Models\Client\ClientEmail;


class ClientEmailService {
    public function allClientEmails(int $clientId)
    {
        return ClientEmail::where('client_id', $clientId)->get();
    }

    public function createClientEmail(array $data)
    {
        return  ClientEmail::create([
            'client_id' => $data['clientId'],
            'email' => $data['email'],
            'is_main' =>IsMain::from($data['isMain'])->value ,
        ]);
    }

    public function editClientEmail(int $id)
    {
        return ClientEmail::find($id);
    }
    public function updateClientEmail(int $id,array $data)
    {
        $ClientEmail=ClientEmail::find($id);
        $ClientEmail->update([
            'client_id' => $data['clientId'],
            'email' => $data['email'],
            'is_main' =>IsMain::from($data['isMain'])->value ,
        ]);
        return $ClientEmail;
    }
    public function deleteClientEmail(int $clientId)
    {
        $ClientEmail=ClientEmail::find($clientId);
        $ClientEmail->delete();
    }
}


