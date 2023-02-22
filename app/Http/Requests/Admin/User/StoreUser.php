<?php

namespace App\Http\Requests\Admin\User;

use App\Traits\Hashable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class StoreUser extends FormRequest
{
    use Hashable;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.user.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'email' => ['required', 'email', Rule::unique('users', 'email'), 'string'],
            'password' => ['required', 'confirmed', 'min:7', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/', 'string'],
            'activated' => ['required', 'boolean'],
            'forbidden' => ['required', 'boolean'],

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
