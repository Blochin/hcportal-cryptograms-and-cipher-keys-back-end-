<?php

namespace App\Http\Requests\Admin\User;

use App\Traits\Hashable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
{
    use Hashable;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.user.edit', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($this->user->getKey(), $this->user->getKeyName()), 'string', 'max:255'],
            'password' => ['sometimes', 'confirmed', 'min:7', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string', 'max:255'],
            'activated' => ['sometimes', 'boolean'],
            'forbidden' => ['sometimes', 'boolean'],

        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();
        if (isset($sanitized['password'])) {
            $sanitized['password'] = $this->hash($sanitized['password']);
        }


        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
