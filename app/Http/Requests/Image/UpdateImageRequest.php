<?php

namespace App\Http\Requests\Image;

use App\Enums\Images\MediaTypeEnum;
use App\Enums\Images\IsMainMediaEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateImageRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'path'=>'required',
            'mediaType'=>['required',new Enum(MediaTypeEnum::class)],
            'isMain'=>['required',new Enum(IsMainMediaEnum::class)]
        ];
    }
}
