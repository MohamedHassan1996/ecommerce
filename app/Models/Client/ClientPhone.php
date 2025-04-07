<?php

namespace App\Models\Client;

use App\Enums\Client\IsMainClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientPhone extends Model
{
    use HasFactory;
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
