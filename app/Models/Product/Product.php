<?php

namespace App\Models\Product;

use App\Enums\Product\LimitedQuantity;
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
        'cost',
        'price',
        'status',
        'is_limited_quantity',
        'quantity',
        'category_id',
        'sub_category_id'
    ];

    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
            'is_limited_quantity' => LimitedQuantity::class
        ];
    }
    public function productMedia()
    {
        return $this->hasMany(ProductMedia::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

}
