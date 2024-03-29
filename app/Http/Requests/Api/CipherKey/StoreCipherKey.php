<?php

namespace App\Http\Requests\Api\CipherKey;

use App\Http\Requests\Api\JsonFormRequest;
use App\Models\CipherKey;
use App\Models\Language;
use App\Models\Location;
use App\Rules\SameLengthAsArray;
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
            'availability' => ['nullable', 'string', 'max:255', Rule::requiredIf(function () {
                return $this->input('archive') == null;
            })],
            'description' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255', Rule::unique('cipher_keys', 'name')],
            'complete_structure' => ['required', 'string'],
            'used_chars' => ['nullable', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'exists:categories,id'],
            'key_type' => ['required', 'integer', 'exists:key_types,id'],
            'used_from' => ['nullable', 'date'],
            'used_to' => ['nullable', 'date'],
            'used_around' => ['nullable', 'string', 'max:255'],
            'archive' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null;
            })],
            'fond' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null;
            })],
            'folder' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null;
            })],
            'location_name' => ['nullable', 'string', 'max:255'],
            'language_id' => ['required', 'integer'],
            'users' => ['nullable', 'json'],
            'images' => ['nullable', 'json'],
            'files' => ['nullable'],
            'state' => ['nullable', 'string', 'max:255', Rule::in(collect(CipherKey::STATUSES)->pluck('id')->toArray())],
            'files.*' => ['image'],
            'tags' => ['nullable', 'array'],
            'continent' => ['nullable', 'string', 'exists:locations,continent'],
            'cryptograms_id' => ['nullable', 'array']

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
            'availability' => [
                'description' => 'Availability',
            ],
            'complete_structure' => [
                'description' => 'Complete structure',
            ],
            'description' => [
                'description' => 'Description',
            ],
            'name' => [
                'description' => 'Cipher key name',
            ],
            'used_chars' => [
                'description' => 'Category the post belongs to.',
            ],
            'category_id' => [
                'description' => 'The ID of Category',
                'example' => 1,
            ],
            'subcategory_id' => [
                'description' => 'The ID of Category children',
                'example' => 2,
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
            'used_around' => [
                'description' => 'Used around.',
            ],
            'folder' => [
                'description' => 'The Folder name',
                'example' => 'Folder name',
            ],
            'archive' => [
                'description' => 'The Archive name',
                'example' => 'Archive name',
            ],
            'fond' => [
                'description' => 'The Fond name',
                'example' => 'Fond name',
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
            'state' => [
                'description' => 'State',
                'example' => 'approved',
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

        if (auth()->user()->hasRole('admin')) {
            $sanitized['state'] =  CipherKey::STATUS_APPROVED;
        } else {
            $sanitized['state'] = CipherKey::STATUS_AWAITING;
        }


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

        if (isset($sanitized['subcategory_id']) && $sanitized['subcategory_id']) {
            $sanitized['category_id'] = $sanitized['subcategory_id'] ? $sanitized['subcategory_id'] : null;
        }

        $sanitized['location_id'] = $location->id;

        return $sanitized;
    }
}
