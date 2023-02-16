<?php

namespace App\Http\Requests\Api\CipherKey;

use App\Http\Requests\Api\JsonFormRequest as FormRequest;

class ApprovedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orderBy' => 'in:id,signature|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'detailed' => 'boolean|nullable'
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

        return $sanitized;
    }
}
