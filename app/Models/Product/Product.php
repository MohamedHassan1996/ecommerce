<?php

namespace App\Models\Product;

use App\Enums\Product\ProductStatus;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
    ];

    protected function casts(): array
    {
        return [

            'status' => ProductStatus::class
        ];
    }
}
