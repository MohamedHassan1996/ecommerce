<?php

namespace App\Http\Controllers\Api\V1\Dashboard\ProductMedia;

use App\Enums\ResponseCode\HttpStatusCode;
use App\Helpers\ApiResponse;
use App\Models\ProductMedia\ProductMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Image\StoreImageRequest;
use App\Http\Requests\Image\UpdateImageRequest;

class ProductMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ProductMedia=ProductMedia::paginate();
        return ApiResponse::success($ProductMedia);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $storeImageRequest)
    {

       $ProductMedia= ProductMedia::create($storeImageRequest->validated());

       return ApiResponse::success([],__('crud.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id ,UpdateImageRequest $updateImageRequest )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image=ProductMedia::find($id);
        $image->delete();
        return ApiResponse::success([],__('crud.deleted'));
    }
}
