<?php

namespace App\Http\Requests\Client;

use App\Enums\Client\IsMainClient;
use App\Enums\Client\AddableToBulk;
use Illuminate\Validation\Rules\Enum;
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
            'notes' => 'nullable|string',
            'phones'=>'nullable|array',//phone ,is_main , country_code
            'phones.*.phone'=>'required|unique:phones,phone|max:255',
            'phones.*.isMain'=>['required',new Enum(IsMainClient::class)],
            'phones.*.countryCode'=>'nullable|string|max:255',
            'emails'=>'nullable|array',//email ,is_main
            'emails.*.isMain'=>['required',new Enum(IsMainClient::class)],
            'emails.*.email'=>'required|email|unique:emails,email|max:255',
            'addresses'=>'nullable|array',//address ,is_main
            'addresses.*.address'=>'required|string|unique:addresses,address|max:255',
            'addresses.*.isMain'=>['required',new Enum(IsMainClient::class)],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

}
