<?php

namespace App\Http\Requests\Api\CipherKey;

use App\Http\Requests\Api\JsonFormRequest;
use App\Models\CipherKey;
use App\Models\Language;
use App\Models\Location;
use Illuminate\Validation\Rule;

class StoreCipherKey extends JsonFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
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
            'signature' => ['required', 'string', Rule::unique('cipher_keys', 'signature')],
            'complete_structure' => ['required', 'string'],
            'used_chars' => ['nullable', 'string'],
            'cipher_type' => ['required', 'integer', 'exists:cipher_types,id'],
            'key_type' => ['required', 'integer', 'exists:key_types,id'],
            'used_from' => ['nullable', 'date'],
            'used_to' => ['nullable', 'date'],
            'new_folder' => ['nullable', 'string'],
            'new_fond' => ['nullable', 'string'],
            'new_archive' => ['nullable', 'string'],
            'used_around' => ['nullable', 'string'],
            'folder_id' => 'nullable|required_if:new_folder,null|integer|exists:folders,id',
            'archive_id' => 'nullable|required_if:new_archive,null|integer|exists:archives,id',
            'fond_id' => 'nullable|required_if:new_fond,null|integer|exists:fonds,id',
            'location_name' => ['nullable', 'string'],
            'language_id' => ['required', 'integer'],
            'users' => ['nullable', 'json'],
            'images' => ['nullable', 'json'],
            'files' => ['nullable',],
            'tags' => ['nullable', 'array'],
            'continent' => ['nullable', 'string', 'exists:locations,continent'],

        ];
    }

    /**
     * API documntation description
     *
     * @return void
     */
    public function bodyParameters()
    {
        return [
            'complete_structure' => [
                'description' => 'Complete structure',
            ],
            'description' => [
                'description' => 'Description',
            ],
            'signature' => [
                'description' => 'Date to be used as the publication date.',
            ],
            'used_chars' => [
                'description' => 'Category the post belongs to.',
            ],
            'cipher_type' => [
                'description' => 'Cipher type',
            ],
            'key_type' => [
                'description' => 'Key type',
            ],
            'used_to' => [
                'description' => 'Used to. Format: Y-m-d',
                'example' => '2022-02-28',
            ],
            'used_from' => [
                'description' => 'Used from. Format: Y-m-d',
                'example' => '2022-02-28',
            ],
            'new_folder' => [
                'description' => 'New folder.',
            ],
            'new_fond' => [
                'description' => 'New fond.',
            ],
            'new_archive' => [
                'description' => 'New archive.',
            ],
            'used_around' => [
                'description' => 'Used around.',
            ],
            'folder_id' => [
                'description' => 'The ID of Folder',
                'example' => 1,
            ],
            'archive_id' => [
                'description' => 'The ID of Archive',
                'example' => 1,
            ],
            'fond_id' => [
                'description' => 'The ID of Fond',
                'example' => 1,
            ],
            'language_id' => [
                'description' => 'The ID of Language',
                'example' => 2,
            ],
            'location_name' => [
                'description' => 'Specific name of the location',
                'example' => 'Bratislava',
            ],
            'continent' => [
                'description' => 'Continent.',
                'example' => 'Europe',
            ],
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

        $sanitized['images'] = $sanitized['images'] ? json_decode($sanitized['images']) : null;
        $sanitized['users'] = $sanitized['users'] ? json_decode($sanitized['users']) : null;

        $sanitized['created_by'] = auth()->user()->id;
        $sanitized['state'] = CipherKey::STATUS_AWAITING;


        if (isset($sanitized['location_name']) && isset($sanitized['continent'])) {
            $location = Location::firstOrCreate([
                'name' => $sanitized['location_name'],
                'continent' => $sanitized['continent']
            ]);
        } elseif (isset($sanitized['continent'])) {
            $location = Location::firstOrCreate([
                'continent' => $sanitized['continent']
            ]);
        } else {
            $location = Location::firstOrCreate([
                'continent' => 'Unknown'
            ]);
        }

        $sanitized['location_id'] = $location->id;

        return $sanitized;
    }
}
