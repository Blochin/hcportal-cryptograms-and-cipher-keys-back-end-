<?php

namespace App\Http\Requests\Admin\Cipher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCipher extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.cipher.edit', $this->cipher);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'availability' => ['sometimes', 'string'],
            'category_id' => ['sometimes', 'string'],
            'day' => ['sometimes', 'integer'],
            'description' => ['sometimes', 'string'],
            'flag' => ['sometimes', 'boolean'],
            'image_url' => ['sometimes', 'string'],
            'language_id' => ['sometimes', 'string'],
            'location_id' => ['sometimes', 'string'],
            'month' => ['sometimes', 'integer'],
            'name' => ['sometimes', 'string'],
            'recipient_id' => ['sometimes', 'string'],
            'sender_id' => ['sometimes', 'string'],
            'solution_id' => ['sometimes', 'string'],
            'state_id' => ['nullable', 'string'],
            'year' => ['sometimes', 'integer'],
            
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


        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
