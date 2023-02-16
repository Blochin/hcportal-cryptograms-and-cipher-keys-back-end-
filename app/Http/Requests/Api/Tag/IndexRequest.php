<?php

namespace App\Http\Requests\Api\Tag;

use App\Http\Requests\Api\JsonFormRequest as FormRequest;

class IndexRequest extends FormRequest
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
            'orderBy' => 'in:id,name,type|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'type' => 'in:cipher_key,cryptogram|nullable',
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
