<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\JsonFormRequest as FormRequest;

class IndexApiRequest extends FormRequest
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
            'orderBy' => 'in:name,continent|nullable',
            'orderDirection' => 'in:asc,desc|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
