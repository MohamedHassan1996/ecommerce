<?php
namespace App\Services\ProductMedia;

use App\Helpers\ApiResponse;
use App\Services\Upload\UploadService;
use Illuminate\Support\Facades\Storage;
use App\Models\Product\ProductMedia;
use App\Enums\ResponseCode\HttpStatusCode;


class ProductMediaService{
    public  $uploadService;
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService =$uploadService;
    }
    public function allProductMedia($productId){
        return ProductMedia::where('product_id',$productId)->get();
    }
    public function createProductMedia(array $data){
     return  ProductMedia::create([
            'path'=>$data['path'],
            'media_type'=>$data['mediaType'],
            'is_main'=>$data['isMain'],
            'product_id'=>$data['productId'],
            ]);

    }
    public function editProductMedia(int $id){
        return ProductMedia::findOrFail($id);
    }

    public function updateProductMedia(int $id,array $data){
        $productMedia=ProductMedia::findOrFail($id);
        if(!$productMedia){
           return ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        }
        $path = null;
        if(isset($data['path'])){
            Storage::disk('public')->delete($productMedia->path);
            $path = $this->uploadService->uploadFile($data['path'], 'media');
        }
        $productMedia->update([
            'path'=>$path,
            'media_type'=>$data['mediaType'],
            'is_main'=>$data['isMain'],
            'product_id'=>$data['productId'],
        ]);
        return $productMedia;
    }

    public function deleteProductMedia(int $id){
        $productMedia=ProductMedia::find($id);
        Storage::disk('public')->delete($productMedia->getRawOriginal('path'));
        $productMedia->delete();
    }

}

