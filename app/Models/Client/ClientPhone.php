<?php

namespace App\Models\Client;

use App\Enums\Client\IsMainClient;
use Illuminate\Database\Eloquent\Model;

class ClientPhone extends Model
{
    protected $table = 'phones';
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    protected $casts = [
        'is_main' => IsMainClient::class,
    ];
}
