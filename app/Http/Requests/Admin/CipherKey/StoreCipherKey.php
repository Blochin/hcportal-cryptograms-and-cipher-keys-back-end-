<?php

namespace App\Http\Requests\Admin\CipherKey;

use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreCipherKey extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.cipher-key.create');
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
            'cryptograms' => ['nullable'],

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
        $sanitized['images'] = $sanitized['images'] ? json_decode($sanitized['images']) : null;
        $sanitized['tags'] = $sanitized['tags'] ? json_decode($sanitized['tags']) : [];
        $sanitized['group_id'] = $sanitized['group'] ? json_decode($sanitized['group'])->id : null;
        $sanitized['language_id'] = $sanitized['language'] ? json_decode($sanitized['language'])->id : null;
        $sanitized['location_id'] = $sanitized['location'] ? json_decode($sanitized['location'])->id : null;
        $sanitized['cipher_type'] = $sanitized['cipher_type'] ? json_decode($sanitized['cipher_type'])->id : null;
        $sanitized['key_type'] = $sanitized['key_type'] ? json_decode($sanitized['key_type'])->id : null;
        $sanitized['folder_id'] = $sanitized['folder'] ? json_decode($sanitized['folder'])->id : null;
        $sanitized['fond_id'] = $sanitized['fond'] ? json_decode($sanitized['fond'])->id : null;
        $sanitized['archive_id'] = $sanitized['archive'] ? json_decode($sanitized['archive'])->id : null;
        $sanitized['created_by'] = auth()->user()->id;
        $sanitized['cryptograms'] = $sanitized['cryptograms'] ? json_decode($sanitized['cryptograms']) : [];

        $state = State::create([
            'name' => $sanitized['complete_structure'] ?: $sanitized['signature'],
            'state' => State::STATUS_NEW,
            'created_by' => auth()->user()->id
        ]);

        $sanitized['state_id'] = $state->id;

        return $sanitized;
    }
}
