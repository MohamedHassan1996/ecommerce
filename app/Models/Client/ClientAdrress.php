<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientAdrress extends Model
{
    use HasFactory;
    protected $table = 'client_addresses';
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
