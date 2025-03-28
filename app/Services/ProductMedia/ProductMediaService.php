<?php
namespace App\Services\ProductMedia;

use App\Helpers\ApiResponse;
use App\Services\Upload\UploadService;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductMedia\ProductMedia;
use App\Enums\ResponseCode\HttpStatusCode;
use GuzzleHttp\Psr7\Request;

class ProductMediaService{
    public  $uploadService;
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService =$uploadService;
    }
    public function all($productId){
        $ProductMedia=ProductMedia::where('product_id',$productId)->get();
        return $ProductMedia;
    }
    public function store(array $data){
            $ProductMedia= ProductMedia::create([
            'path'=>$data['path'],
            'media_type'=>$data['mediaType'],
            'is_main'=>$data['isMain'],
            'product_id'=>$data['productId'],
            ]);

        return $ProductMedia;

    }
    public function edit(int $id){
        $ProductMedia=ProductMedia::findOrFail($id);
        return $ProductMedia;
    }

    public function update(int $id,array $data){
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

    public function delete(int $id){
        $productMedia=ProductMedia::find($id);
        if(!$productMedia){
            return ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        }
        $productMedia->delete();
        Storage::disk('public')->delete($productMedia->getRawOriginal('path'));
        return "deleted";
    }

}
?>
