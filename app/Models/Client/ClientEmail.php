<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientEmail extends Model
{
    protected $table = 'emails';
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
