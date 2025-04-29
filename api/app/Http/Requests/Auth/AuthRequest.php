<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Response;

class AuthRequest extends FormRequest
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
            'email' => 'string|email|required',
            'password' => 'string|required|min:6'
        ];
    }

    public function messages(): array {
        return [
            'email.string' => ':attribute không đúng định dạng',
            'email.email' => ':attribute không đúng định dạng Email ví dụ: abc@gmail.com',
            'email.required' => ':attribute không được để trống',
            'password.string' => ':attribute không đúng định dạng',
            'password.required' => ':attribute không được để trống',
            'password.min' => ':attribute phải có tối thiểu 6 kí tự'
        ];
    }

    public function attributes(): array {
        return [
            'email' => 'Email',
            'password' => 'Mật khẩu'
        ];
    }

    public function failedValidation(Validator $validator){
        $resource = ApiResource::error($validator->errors(), "Kiểm tra dữ liệu không thành công", Response::HTTP_UNPROCESSABLE_ENTITY);
        throw new HttpResponseException($resource);
    }

}
