<?php
namespace App\Http\Requests\Post\Post;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

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
            'id' => 'sometimes|required|exists:posts',
            'publish' => 'sometimes|required|min:1|max:2',
            'user_id' => 'sometimes|required|exists:users,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('post')
        ]);
    }
}
