<?php

namespace App\Http\Controllers\Api\V1\Dashboard\ProductMedia;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use App\Services\Upload\UploadService;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Services\ProductMedia\ProductMediaService;
use App\Http\Resources\ProductMedia\ProductMediaResouce;
use App\Http\Requests\ProductMedia\CreateProductMediaRequest;
use App\Http\Requests\ProductMedia\UpdateProductMediaRequest;
use App\Http\Resources\ProductMedia\AllProductMediaCollection;

class ProductMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public  $productMediaService;
    public $uploadService;
    public function __construct(ProductMediaService $productMediaService ,UploadService $uploadService)
    {
        $this->productMediaService =$productMediaService;
        $this->uploadService =$uploadService;
    }
    public function index(Request $request)
    {
        $ProductMedia= $this->productMediaService->allProductMedia($request->productId);
        return ApiResponse::success(new AllProductMediaCollection(PaginateCollection::paginate($ProductMedia, $request->pageSize?$request->pageSize:10)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductMediaRequest $createProductMediaRequest)
    {
       try{
            $data =$createProductMediaRequest->validated();
            foreach($data['productMedia'] as $media){
                if(isset($media['path'])){
                    $path = $this->uploadService->uploadFile($media['path'], 'media');
                }
                $media['path'] = $path;
                $this->productMediaService->createProductMedia($media);
            }

            return ApiResponse::success([],__('crud.created'));
        }catch(\Exception $e){
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $productMedia= $this->productMediaService->editProductMedia($id);
            return ApiResponse::success( new ProductMediaResouce($productMedia));
        } catch (\Throwable $th) {
            return ApiResponse::error(__('crud.not_found'),[],HttpStatusCode::NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id ,UpdateProductMediaRequest $updateProductMediaRequest )
    {
        try{
        $this->productMediaService->updateProductMedia($id,$updateProductMediaRequest->validated());
        return ApiResponse::success([],__('crud.updated'));
        }catch(\Exception $e){
            return ApiResponse::error(__('crud.server_error'),[],HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //getRawOriginal('path')
        $this->productMediaService->deleteProductMedia($id);
        return ApiResponse::success([],__('crud.deleted'));
    }
}
