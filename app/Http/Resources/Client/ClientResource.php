<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\Client\ClientAddress\AllClientAddressResource;
use App\Http\Resources\Client\ClientContact\AllClientContactResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'clientId' => $this->id,
            'name' => $this->name,
            'notes' => $this->notes,
            'addresses' => AllClientAddressResource::collection($this->whenLoaded('addresses')),
            'phones' => AllClientContactResource::collection($this->whenLoaded('phones')),
        ];
    }
}
