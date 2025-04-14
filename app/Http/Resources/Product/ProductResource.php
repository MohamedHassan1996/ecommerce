<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\ProductMedia\ProductMediaResouce;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'productId' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'status' => $this->status,
            'description' => $this->description??"",
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'productMedia' => ProductMediaResouce::collection($this->whenLoaded('productMedia')),
        ];
    }
}
