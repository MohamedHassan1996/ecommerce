<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Product;

use App\Helpers\ApiResponse;
use App\Http\Resources\Product\ProductEditResource;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Upload\UploadService;
use App\Services\Product\ProductService;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Http\Requests\Product\StoreProductRequest;
use App\Services\ProductMedia\ProductMediaService;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\AllProductResource;
use App\Http\Resources\ProductMedia\AllProductMedia;

class ProductController extends Controller
{
    public $productService;
    public function __construct( ProductService $productService)
    {
        $this->productService =$productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      $products= $this->productService->all();
       return ApiResponse::success(new AllProductResource(PaginateCollection::paginate($products, $request->pageSize?$request->pageSize:10)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $storeProductRequest)
    {
        try {
            DB::beginTransaction();
            $data= $storeProductRequest->validated();
            $this->productService->store($data);
            DB::commit();
            return ApiResponse::success([],__('crud.created'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error(__('crud.not_created'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $product= $this->productService->edit($id);
        return ApiResponse::success(new ProductEditResource($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $updateProductRequest, int $id)
    {
        DB::beginTransaction();
        $data= $updateProductRequest->validated();
        $this->productService->update($id,$data);
        DB::commit();
        return ApiResponse::success([], __('crud.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
          $this->productService->delete($id);
        return ApiResponse::success([],__('crud.deleted'));
    }
}
