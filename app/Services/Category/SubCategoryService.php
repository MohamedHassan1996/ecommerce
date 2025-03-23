<?php

namespace App\Services\Category;

use App\Enums\Product\CategoryStatus;
use App\Models\Product\Category;
use App\Services\Upload\UploadService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubCategoryService{

    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function allSubCategories()
    {
        $subCategories = QueryBuilder::for(Category::class)
        ->allowedFilters([
            AllowedFilter::exact('categoryId', 'parent_id')
        ])
        ->get();

        return $subCategories;

    }

    public function createSubCategory(array $subCategoryData): Category
    {

        $path = isset($subCategoryData['subCategoryPath'])? $this->uploadService->uploadFile($subCategoryData['subCategoryPath'], 'categories'):null;

        $subCategory = Category::create([
            'name' => $subCategoryData['subCategoryName'],
            'is_active' => CategoryStatus::from($subCategoryData['subCategoryIsActive'])->value,
            'path' => $path,
            'parent_id' => $subCategoryData['categoryId'],
        ]);

        return $subCategory;

    }

    public function editSubCategory(int $subCategoryId): Category
    {
        return Category::findOrFail($subCategoryId);
    }

    public function updateSubCategory(array $subCategoryData)
    {

        $path = null;

        if(isset($subCategoryData['subCategoryPath'])){
            $path = isset($subCategoryData['subCategoryPath'])? $this->uploadService->uploadFile($subCategoryData['subCategoryPath'], 'categories'):null;
        }

        $subCategory = Category::find($subCategoryData['subCategoryId']);
        $subCategory->name = $subCategoryData['subCategoryName'];
        $subCategory->path = $path;
        $subCategory->is_active = CategoryStatus::from($subCategoryData['subCategoryIsActive'])->value;
        $subCategory->save();

        return $subCategory;

    }


    public function deleteSubCategory(int $subCategoryId)
    {

        Category::find($subCategoryId)->delete();

    }

}
