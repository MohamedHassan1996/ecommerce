<?php
 namespace App\Services\Client;

use App\Models\Client\ClientAdrress;
use Illuminate\Support\Facades\Request;

    class ClientAddressService
    {
        public function all( $clientId)
        {
            $clientAddresses =ClientAdrress::where('client_id',$clientId)->get();
            return $clientAddresses;
        }
        public function edit($id)
        {
            $clientAddress = ClientAdrress::find($id);
            return $clientAddress;
        }
        public function create(array $data)
        {
          $ClientAdrress=ClientAdrress::create([
                'client_id' => $data['clientId'],
                'address' => $data['address'],
                'is_main' => $data['isMain'],
            ]);
            return $ClientAdrress;
        }
        public function update(int $id , array $data)
        {
            $clientAddress = ClientAdrress::find($id);
            if ($clientAddress) {
                $clientAddress->update([
                    'client_id' => $data['clientId'],
                    'address' => $data['address'],
                    'is_main' => $data['isMain'],
                ]);
            }
            return $clientAddress;
        }
        public function delete($id)
        {
            $clientAddress = ClientAdrress::find($id);
            if ($clientAddress) {
                $clientAddress->delete();
            }
            return $clientAddress;
        }
    }
