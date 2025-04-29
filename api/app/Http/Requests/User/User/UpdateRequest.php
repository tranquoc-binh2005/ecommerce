<?php

namespace App\Http\Requests\User\User;

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
            'id' => 'required|exists:users',
            'name' => 'sometimes|required|string',
            'email' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('users')->ignore($this->route('user'))
            ],
            'phone' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('users')->ignore($this->route('user'))
            ],
            'birthday' => 'sometimes|required',
            'publish' => 'sometimes|required|min:1|max:2',
            'user_id' => 'sometimes|required|exists:users,id',
            'user_catalogues' => 'sometimes|required|array',
            'user_catalogues.*' => 'sometimes|integer|required|exists:user_catalogues,id'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('user')
        ]);
    }

}
