<?php

namespace App\Http\Requests\Admin\CipherKeySimilarity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCipherKeySimilarity extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.cipher-key-similarity.edit', $this->cipherKeySimilarity);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'cipher_keys' => ['required', 'array']

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
        $sanitized['cipher_keys'] = collect($sanitized['cipher_keys'])->pluck('id')->toArray();

        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
