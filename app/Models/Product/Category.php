<?php

namespace App\Models\Product;

use App\Enums\Product\CategoryStatus;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Category extends Model
{
    use CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'parent_id',
        'is_active',
        'path',
    ];

    public function casts()
    {
        return [
            'is_active' => CategoryStatus::class,
        ];
    }

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::disk('public')->url($value) : "",
        );
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

}
