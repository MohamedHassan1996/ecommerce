<?php
namespace App\Services\Client;
use App\Models\Client\Client;
use App\Models\Client\ClientEmail;
use App\Models\Client\ClientPhone;
use App\Models\Client\ClientAdrress;

class ClientService
{
    protected $clientService;
    protected $clientPhoneService;
    protected $clientEmailService;
    protected $clientAddressService;
   public function __construct(  ClientPhoneService $clientPhoneService, ClientEmailService $clientEmailService, ClientAddressService $clientAddressService)
    {
        $this->clientPhoneService = $clientPhoneService;
        $this->clientEmailService = $clientEmailService;
        $this->clientAddressService = $clientAddressService;
    }
    public function all()
    {
        $client=Client::with(['emails', 'phones', 'addresses'])->get();
        return $client;
    }
    public function edit( $id)
    {
        $client=Client::with(['emails', 'phones', 'addresses'])->find($id);
        return $client;
    }
    public function store(array $data)
    {
      $client=Client::create([
        'name'=>$data['name'],
        'notes'=>$data['notes'],
      ]);
      if (isset($data['phones'])) {
        foreach ($data['phones'] as $phone) {
            $this->clientPhoneService->create(['clientId'=>$client->id, ...$phone]);
        }
    }
    if (isset($data['emails'])) {
        foreach ($data['emails'] as $email) {
            $this->clientEmailService->create(
                ['clientId'=>$client->id, ...$email]);

        }
    }
    if (isset($data['addresses'])) {
        foreach ($data['addresses'] as $address) {
            $this->clientAddressService->create(['clientId'=> $client->id, ...$address]);
        }
    }
      return $client;
    }
    public function update($id,array $data )
    {
        $client=Client::find($id);
        $client->update([
            'name'=>$data['name'],
            'notes'=>$data['notes'],
        ]);
        return $client;
    }
    public function destroy($id)
    {
        $client=Client::find($id);
        $client->delete();
        return $client;
    }
}
