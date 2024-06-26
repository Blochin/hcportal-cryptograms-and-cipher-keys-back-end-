<?php

namespace App\Http\Requests\Admin\Cryptogram;

use App\Models\Location;
use App\Models\Person;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCryptogram extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.cryptogram.edit', $this->cryptogram);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'availability_type' => ['required', 'string'],
            'availability' => ['nullable', 'string', 'max:255', Rule::requiredIf(function () {
                return $this->input('availability_type') == "availability";
            })],
            'category' => ['required', 'string'],
            'subcategory' => ['nullable', 'string'],
            'used_chars' => ['nullable', 'string'],

            'description' => ['nullable', 'string'],
            'language' => ['required', 'string'],
            'location_name' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'recipient' => ['nullable', 'string'],
            'sender' => ['nullable', 'string'],
            'solution' => ['required', 'string'],
            'state_id' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'date_around' => ['nullable', 'string', 'max:255'],

            'folder' => ['nullable', Rule::requiredIf(function () {
                return $this->input('new_folder') == null && $this->input('availability_type') == "archive";
            })],
            'archive' => ['nullable', Rule::requiredIf(function () {
                return $this->input('new_archive') == null && $this->input('availability_type') == "archive";
            })],
            'fond' => ['nullable', Rule::requiredIf(function () {
                return $this->input('new_fond') == null && $this->input('availability_type') == "archive";
            })],

            'new_folder' => ['nullable', 'string', Rule::requiredIf(function () {
                return $this->input('folder') == null && $this->input('availability_type') == "archive";
            })],
            'new_fond' => ['nullable', 'string', Rule::requiredIf(function () {
                return $this->input('fond') == null && $this->input('availability_type') == "archive";
            })],
            'new_archive' => ['nullable', 'string', Rule::requiredIf(function () {
                return $this->input('archive') == null && $this->input('availability_type') == "archive";
            })],


            'images' => ['nullable'],
            'images.*.*' => ['image'],
            'image' => ['nullable'],
            'groups' => ['nullable'],
            // 'predefined_groups' => ['nullable'],
            'tags' => ['nullable'],
            'cipher_keys' => ['nullable'],
            'continent' => ['required'],
            'state' => ['required'],
            'note' => ['nullable'],
            'note_new' => ['nullable'],
            'thumbnail' => ['nullable', 'image']

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

        $sanitized['thumbnail_url'] = 'temporary';
        $sanitized['language_id'] = $sanitized['language'] ? json_decode($sanitized['language'])->id : null;
        $sanitized['solution_id'] = $sanitized['solution'] ? json_decode($sanitized['solution'])->id : null;
        $sanitized['sender_id'] = $sanitized['sender'] ? json_decode($sanitized['sender'])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;
        $sanitized['recipient_id'] = $sanitized['recipient'] ? json_decode($sanitized['recipient'])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;

        $sanitized['groups'] = $sanitized['groups'] ? json_decode($sanitized['groups']) : null;

        $sanitized['tags'] = $sanitized['tags'] ? json_decode($sanitized['tags']) : [];

        $sanitized['cipher_keys'] = $sanitized['cipher_keys'] ? json_decode($sanitized['cipher_keys']) : null;

        $sanitized['folder_id'] = $sanitized['folder'] ? json_decode($sanitized['folder'])->id : null;
        $sanitized['fond_id'] = $sanitized['fond'] ? json_decode($sanitized['fond'])->id : null;
        $sanitized['archive_id'] = $sanitized['archive'] ? json_decode($sanitized['archive'])->id : null;
        $sanitized['availability'] = $sanitized['availability'] ?: null;

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

        if ($sanitized['subcategory']) {
            $sanitized['category_id'] = $sanitized['subcategory'] ? json_decode($sanitized['subcategory'])->id : null;
        } elseif ($sanitized['category']) {
            $sanitized['category_id'] = $sanitized['category'] ? json_decode($sanitized['category'])->id : null;
        }

        $sanitized['location_id'] = $location->id;

        return $sanitized;
    }
}
