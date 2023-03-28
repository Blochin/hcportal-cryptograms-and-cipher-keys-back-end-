<?php

namespace App\Http\Requests\Admin\CipherKey;

use App\Models\Location;
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
            'availability' => ['nullable', 'string', Rule::requiredIf(function () {
                return $this->input('archive') == null;
            })],
            'description' => ['nullable', 'string'],
            'name' => ['required', 'string', Rule::unique('cipher_keys', 'name')],
            'complete_structure' => ['required', 'string'],
            'used_chars' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'subcategory' => ['nullable', 'string'],
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
            'used_around' => ['nullable', 'string'],
            'location_name' => ['nullable'],
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
        $sanitized['key_type'] = $sanitized['key_type'] ? json_decode($sanitized['key_type'])->id : null;
        $sanitized['folder_id'] = $sanitized['folder'] ? json_decode($sanitized['folder'])->id : null;
        $sanitized['fond_id'] = $sanitized['fond'] ? json_decode($sanitized['fond'])->id : null;
        $sanitized['archive_id'] = $sanitized['archive'] ? json_decode($sanitized['archive'])->id : null;
        $sanitized['created_by'] = auth()->user()->id;
        $sanitized['cryptograms'] = $sanitized['cryptograms'] ? json_decode($sanitized['cryptograms']) : [];
        $sanitized['continent'] = $sanitized['continent'] ? json_decode($sanitized['continent'])->name : [];
        $sanitized['state'] = $sanitized['state'] ? json_decode($sanitized['state'])->id : [];

        if ($sanitized['subcategory']) {
            $sanitized['category_id'] = $sanitized['subcategory'] ? json_decode($sanitized['subcategory'])->id : null;
        } elseif ($sanitized['category']) {
            $sanitized['category_id'] = $sanitized['category'] ? json_decode($sanitized['category'])->id : null;
        }

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
