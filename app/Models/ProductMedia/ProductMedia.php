<?php

namespace App\Models\ProductMedia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMedia extends Model
{
    /** @use HasFactory<\Database\Factories\Image\ImageFactory> */
    use HasFactory;
    protected $guarded =[];
    protected $table ='images';
}
