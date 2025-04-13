<?php

namespace App\Http\Requests\Client;

use App\Enums\IsMain;
use App\Helpers\ApiResponse;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ResponseCode\HttpStatusCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class CreateClientRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'phones'=>'nullable|array',//phone ,is_main , country_code
            'phones.*.phone'=>'required|unique:client_phones,phone|max:255',
            'phones.*.isMain'=>['required',new Enum(IsMain::class)],
            'phones.*.countryCode'=>'nullable|string|max:255',
            'emails'=>'nullable|array',//email ,is_main
            'emails.*.isMain'=>['required',new Enum(IsMain::class)],
            'emails.*.email'=>'required|email|unique:client_emails,email|max:255',
            'addresses'=>'nullable|array',//address ,is_main
            'addresses.*.address'=>'required|string|unique:client_addresses,address|max:255',
            'addresses.*.isMain'=>['required',new Enum(IsMain::class)],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('', $validator->errors(), HttpStatusCode::UNPROCESSABLE_ENTITY)
        );
    }

}
