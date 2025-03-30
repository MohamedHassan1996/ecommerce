<?php

namespace App\Http\Controllers\Api\V1\Dashboard\ProductMedia;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use App\Services\Upload\UploadService;
use Illuminate\Support\Facades\Storage;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Http\Requests\Image\StoreImageRequest;
use App\Http\Requests\Image\UpdateImageRequest;
use App\Services\ProductMedia\ProductMediaService;
use App\Http\Resources\ProductMedia\AllProductMedia;
use App\Http\Resources\ProductMedia\ProductMediaResouce;

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
        $ProductMedia= $this->productMediaService->all($request->productId);
        return ApiResponse::success(new AllProductMedia(PaginateCollection::paginate($ProductMedia, $request->pageSize?$request->pageSize:10)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $storeImageRequest)
    {
       try{
            $data =$storeImageRequest->validated();
            foreach($data['productMedia'] as $media){
                if(isset($media['path'])){
                    $path = $this->uploadService->uploadFile($media['path'], 'media');
                }
                $media['path'] = $path;
                $this->productMediaService->store($media);
            }

            return ApiResponse::success([],__('crud.created'));
        }catch(\Exception $e){
            return ApiResponse::error(__('crud.failed'),[],HttpStatusCode::UNPROCESSABLE_ENTITY);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ProductMedia= $this->productMediaService->edit($id);
        return ApiResponse::success( new ProductMediaResouce($ProductMedia));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id ,UpdateImageRequest $updateImageRequest )
    {
        try{
            $data=$updateImageRequest->validated();
            $this->productMediaService->update($id,$data);

        return ApiResponse::success([],__('crud.updated'));
        }catch(\Exception $e){
            return ApiResponse::error(__('crud.failed'),[],HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //getRawOriginal('path')
        $this->productMediaService->delete($id);
        return ApiResponse::success([],__('crud.deleted'));
    }
}
