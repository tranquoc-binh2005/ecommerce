<?php

namespace App\Http\Requests\User\User;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
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
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users',
            'phone' => 'required|string|unique:users',
            'birthday' => 'required',
            'password' => 'string|required|min:8',
            'user_catalogues' => 'required|array',
            'user_catalogues.*' => 'integer|required|exists:user_catalogues,id',
            'publish' => 'integer|required'
        ];
    }
}
