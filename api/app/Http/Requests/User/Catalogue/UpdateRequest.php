<?php

namespace App\Http\Requests\User\Catalogue;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
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
            'id' => 'required|exists:user_catalogues',
            'name' => 'sometimes|required|string',
            'canonical' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('user_catalogues')->ignore($this->route('user_catalogue'))
            ],
            'publish' => 'sometimes|required|min:1|max:2',
            'user_id' => 'sometimes|required|exists:users,id',
            'users' => 'sometimes|required|array',
            'users.*' => 'sometimes|required|integer|exists:users,id'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('user_catalogue')
        ]);
    }

}
