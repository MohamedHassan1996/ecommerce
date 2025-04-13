<?php
 namespace App\Services\Client;

use App\Enums\IsMain;
use App\Models\Client\ClientAdrress;
use Illuminate\Support\Facades\Request;

    class ClientAddressService
    {
        public function allClientAddress( $clientId)
        {
            return ClientAdrress::where('client_id',$clientId)->get();
        }
        public function editClientAddress(int $id)
        {
            return ClientAdrress::find($id);
        }
        public function createClientAddress(array $data)
        {
            return ClientAdrress::create([
                'client_id' => $data['clientId'],
                'address' => $data['address'],
                'is_main' => IsMain::from($data['isMain']),
            ]);
        }
        public function updateClientAddress(int $id , array $data)
        {
            $clientAddress = ClientAdrress::find($id);
            $clientAddress->update([
                'client_id' => $data['clientId'],
                'address' => $data['address'],
                'is_main' => IsMain::from($data['isMain']),
            ]);
            return $clientAddress;
        }
        public function deleteClientAddress(int $id)
        {
            $clientAddress = ClientAdrress::find($id);
            $clientAddress->delete();
        }
    }
