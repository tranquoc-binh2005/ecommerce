<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use App\Http\Resources\ApiResource;

class BaseRequest extends FormRequest
{
    public function failedValidation(Validator $validator){
        $resource = ApiResource::error($validator->errors(), "Kiểm tra dữ liệu không thành công", Response::HTTP_UNPROCESSABLE_ENTITY);
        throw new HttpResponseException($resource);
    }
}
