<?php
namespace App\Services\Product;

use App\Enums\Product\LimitedQuantity;
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
    public function allProducts(){
        return QueryBuilder::for(Product::class)
        ->allowedFilters(['name','price','status'])
        ->get();
    }
    public function createProduct(array $data){
        $product= Product::create([
            'name'=>$data['name'],
            'price'=>$data['price'],
            'status'=>$data['status'],
            'description'=>$data['description']??null,
            'category_id'=>$data['categoryId']??null,
            'sub_category_id'=>$data['subCategoryId']??null,
            'quantity'=>$data['quantity']??0,
            'cost'=>$data['cost']??0,
            'is_limited_quantity'=>LimitedQuantity::from($data['isLimitedQuantity'])->value
        ]);
        foreach($data['productMedia'] as $media){
            $path=null;
            if(isset($media['path'])){
                $path = $this->uploadService->uploadFile($media['path'], 'media');
            }
            $media['path'] = $path;
            $media['productId'] = $product->id;
            $this->productMediaService->createProductMedia($media);
        }
        return $product;
    }
    public function editProduct(int $id){
        return Product::with(['categories', 'productMedia'])->find($id);
    }
    public function updateProduct(int $id,array $data){
        $product= Product::find($id);
        $product->update([
            'name'=>$data['name'],
            'price'=>$data['price'],
            'status'=> $data['status'],
            'description'=>$data['description']??null,
            'category_id'=>$data['categoryId']??null,
            'sub_category_id'=>$data['subCategoryId']??null,
            'quantity'=>$data['quantity']??0,
            'cost'=>$data['cost']??0,
            'is_limited_quantity'=>LimitedQuantity::from($data['isLimitedQuantity'])->value
        ]);
        return $product;
    }
    public function deleteProduct(int $id){
        Product::find($id)->delete();
    }

}
