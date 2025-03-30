<?php

namespace App\Models\Product;

use App\Traits\CreatedUpdatedBy;
use App\Enums\Product\ProductStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use CreatedUpdatedBy, HasFactory;

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
    public function productMedia()
    {
        return $this->hasMany(ProductMedia::class);
    }
    public function categorys()
    {
        return $this->belongsToMany(Category::class, 'product_Category');
    }

}
