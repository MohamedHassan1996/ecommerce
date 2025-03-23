<?php

namespace App\Http\Requests\Category;

use App\Enums\Product\CategoryStatus;
use App\Enums\ResponseCode\HttpStatusCode;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;


class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required',
                        Rule::unique('categories', 'name')->where(function ($query) {
                            return $query->whereNull('parent_id'); // Only check uniqueness for main categories
                        }),
                ],
            'subCategories' => 'nullable',
            'isActive' => ['required', new Enum(CategoryStatus::class)],
            'path' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:1024',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        /*throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 422));*/

        throw new HttpResponseException(
            ApiResponse::error('', $validator->errors(), HttpStatusCode::UNPROCESSABLE_ENTITY)
        );
    }

}
