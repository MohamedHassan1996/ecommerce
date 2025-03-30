<?php

namespace App\Models\Client;

use App\Models\Client\ClientEmail;
use App\Models\Client\ClientPhone;
use App\Models\Client\ClientAdrress;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $guarded = [];

    public function phones()
    {
        return $this->hasMany(ClientPhone::class);
    }

    public function addresses()
    {
        return $this->hasMany(ClientAdrress::class);
    }

    public function emails()
    {
        return $this->hasMany(ClientEmail::class);
    }
}
