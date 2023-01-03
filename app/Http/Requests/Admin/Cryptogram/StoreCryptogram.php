<?php

namespace App\Http\Requests\Admin\Cryptogram;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreCryptogram extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.cryptogram.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'availability' => ['required', 'string'],
            'category' => ['required', 'array'],
            'day' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'flag' => ['required', 'boolean'],
            'language' => ['required', 'array'],
            'location' => ['required', 'array'],
            'month' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'recipient' => ['required', 'array'],
            'sender' => ['required', 'array'],
            'solution' => ['required', 'array'],
            'state_id' => ['nullable', 'string'],
            'year' => ['required', 'integer'],

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
