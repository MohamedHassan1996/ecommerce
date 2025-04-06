<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientAdrress extends Model
{
    protected $table = 'addresses';
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
