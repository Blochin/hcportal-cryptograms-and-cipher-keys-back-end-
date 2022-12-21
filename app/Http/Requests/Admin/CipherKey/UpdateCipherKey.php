<?php

namespace App\Http\Requests\Admin\CipherKey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCipherKey extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.cipher-key.edit', $this->cipherKey);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string'],
            'signature' => ['nullable', 'string'],
            'complete_structure' => ['required', 'string'],
            'used_chars' => ['nullable', 'string'],
            'cipher_type' => ['nullable',],
            'key_type' => ['nullable',],
            'used_from' => ['nullable', 'date'],
            'used_to' => ['nullable', 'date'],
            'new_folder' => ['nullable', 'string'],
            'new_fond' => ['nullable', 'string'],
            'new_archive' => ['nullable', 'string'],
            'used_around' => ['nullable', 'string'],
            'folder' => 'nullable|required_if:new_folder,null',
            'archive' => 'nullable|required_if:new_archive,null',
            'fond' => 'nullable|required_if:new_fond,null',
            'location' => ['nullable',],
            'language' => ['required',],
            'group' => ['nullable',],
            'users' => ['nullable',],
            'images' => ['nullable',],
            'files' => ['nullable',],
            'tags' => ['nullable',],

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

        $sanitized['users'] = $sanitized['users'] ? json_decode($sanitized['users']) : null;
        $sanitized['tags'] = $sanitized['tags'] ? json_decode($sanitized['tags']) : [];
        $sanitized['group_id'] = $sanitized['group'] ? json_decode($sanitized['group'])->id : null;
        $sanitized['language_id'] = $sanitized['language'] ? json_decode($sanitized['language'])->id : null;
        $sanitized['location_id'] = $sanitized['location'] ? json_decode($sanitized['location'])->id : null;
        $sanitized['cipher_type'] = $sanitized['cipher_type'] ? json_decode($sanitized['cipher_type'])->id : null;
        $sanitized['key_type'] = $sanitized['key_type'] ? json_decode($sanitized['key_type'])->id : null;
        $sanitized['folder_id'] = $sanitized['folder'] ? json_decode($sanitized['folder'])->id : null;
        $sanitized['fond_id'] = $sanitized['fond'] ? json_decode($sanitized['fond'])->id : null;
        $sanitized['archive_id'] = $sanitized['archive'] ? json_decode($sanitized['archive'])->id : null;


        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
