<?php

namespace App\Http\Requests\Permission\Permission;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
     * Rule::unique('users')->ignore($this->route('users'))
     */
    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|exists:permissions,id',
            'name' => [
                'sometimes',
                'regex:/^[a-z_]+:[a-zA-Z]+$/',
                'string',
                'required',
                Rule::unique('permissions')->ignore($this->route('permission'))
            ],
            'module' => [
                'sometimes',
                'string',
                'required'
            ],
            'value' => [
                'sometimes',
                'string',
                'required',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('permissions')
                        ->where('module', $this->module)
                        ->where('value', $this->value)
                        ->where('id', '<>', $this->route('permission'))
                        ->exists();
            
                    if (!$exists) {
                        $fail("Module và Value không hợp lệ");
                    }
                }
            ],
            'title' => 'sometimes|required',
            'publish' => 'sometimes|required|min:1|max:2',
            'user_id' => 'sometimes|required|exists:users,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('permission')
        ]);
    }
}
