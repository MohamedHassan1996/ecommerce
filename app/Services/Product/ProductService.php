<?php
namespace App\Services\Product;

use App\Helpers\ApiResponse;
use App\Models\Product\Product;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Upload\UploadService;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Services\ProductMedia\ProductMediaService;

class ProductService
{
    public  $productMediaService;
    public $uploadService;
    public function __construct(ProductMediaService $productMediaService ,UploadService $uploadService)
    {
        $this->productMediaService =$productMediaService;
        $this->uploadService =$uploadService;
    }
    public function all(){
        $products= QueryBuilder::for(Product::class)
        ->allowedFilters(['name','price','status'])
        ->get();
        return $products;
    }
    public function store(array $data){
        $product= Product::create([
            'name'=>$data['name'],
            'price'=>$data['price'],
            'status'=>$data['status'],
            'description'=>$data['description']??null,
        ]);
        $product->categorys()->attach($data['categoryIds']);
        foreach($data['productMedia'] as $media){
            if(isset($media['path'])){
                $path = $this->uploadService->uploadFile($media['path'], 'media');
            }
            $media['path'] = $path;
            $media['productId'] = $product->id;
            $this->productMediaService->store($media);
        }
        return $product;
    }
    public function edit(int $id){
        $product= Product::with(['categorys', 'productMedia'])->find($id);
        if(!$product){
            return ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        }
        return $product;
    }
    public function update(int $id,array $data){
        $product= Product::find($id);
        if(!$product){
            return ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        }
        $product->update([
            'name'=>$data['name'],
            'price'=>$data['price'],
            'status'=> $data['status'],
            'description'=>$data['description']??null,
        ]);
        $product->categorys()->sync($data['categoryIds']);
    }
    public function delete(int $id){
        $product= Product::find($id);
        $product->delete();
    }

}
?>
