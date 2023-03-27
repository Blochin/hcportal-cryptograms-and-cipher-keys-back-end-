<?php

namespace App\Http\Requests\Admin\CipherKey;

use App\Models\Location;
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
            'availability' => ['nullable', 'string', Rule::requiredIf(function () {
                return $this->input('archive') == null;
            })],
            'description' => ['nullable', 'string'],
            'signature' => ['nullable', 'string', Rule::unique('cipher_keys', 'signature')->ignore($this->signature, 'signature')],
            'complete_structure' => ['required', 'string'],
            'used_chars' => ['nullable', 'string'],
            'cipher_type' => ['nullable',],
            'key_type' => ['nullable',],
            'used_from' => ['nullable', 'date'],
            'used_to' => ['nullable', 'date'],
            'new_folder' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null && $this->input('folder') == null;
            })],
            'new_fond' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null && $this->input('fond') == null;
            })],
            'new_archive' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null && $this->input('archive') == null;
            })],
            'archive' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null && $this->input('new_archive') == null;
            })],
            'fond' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null && $this->input('new_fond') == null;
            })],
            'folder' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null && $this->input('new_folder') == null;
            })],
            'location_name' => ['nullable',],
            'language' => ['required',],
            'group' => ['nullable',],
            'users' => ['nullable',],
            'images' => ['nullable',],
            'files' => ['nullable',],
            'tags' => ['nullable',],
            'cryptograms' => ['nullable'],
            'continent' => ['required'],
            'state' => ['required'],
            'note' => ['nullable'],
            'note_new' => ['nullable'],

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
        $sanitized['cipher_type'] = $sanitized['cipher_type'] ? json_decode($sanitized['cipher_type'])->id : null;
        $sanitized['key_type'] = $sanitized['key_type'] ? json_decode($sanitized['key_type'])->id : null;
        $sanitized['folder_id'] = $sanitized['folder'] ? json_decode($sanitized['folder'])->id : null;
        $sanitized['fond_id'] = $sanitized['fond'] ? json_decode($sanitized['fond'])->id : null;
        $sanitized['archive_id'] = $sanitized['archive'] ? json_decode($sanitized['archive'])->id : null;
        $sanitized['cryptograms'] = $sanitized['cryptograms'] ? json_decode($sanitized['cryptograms']) : [];
        $sanitized['continent'] = $sanitized['continent'] ? json_decode($sanitized['continent'])->name : [];
        $sanitized['state'] = $sanitized['state'] ? json_decode($sanitized['state'])->id : [];

        if ($sanitized['location_name'] && $sanitized['continent']) {
            $location = Location::firstOrCreate([
                'name' => $sanitized['location_name'],
                'continent' => $sanitized['continent']
            ]);
        } elseif ($sanitized['continent']) {
            $location = Location::firstOrCreate([
                'continent' => $sanitized['continent']
            ]);
        }

        $sanitized['location_id'] = $location->id;

        return $sanitized;
    }
}
