<?php

namespace App\Http\Requests\Api\Cryptogram;

use App\Models\CipherKey;
use App\Models\Language;
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
            'availability' => ['nullable', 'string', Rule::requiredIf(function () {
                return $this->input('archive') == null;
            })],
            'category_id' => ['required', 'string', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'string', 'exists:categories,id'],

            'description' => ['nullable', 'string'],
            'language_id' => ['required', 'integer', 'exists:languages,id'],
            'location_name' => ['nullable', 'string'],

            'used_chars' => ['nullable', 'string'],

            'name' => ['required', 'string'],
            'recipient' => ['nullable', 'string'],
            'sender' => ['nullable', 'string'],
            'solution_id' => ['required', 'integer', 'exists:solutions,id'],
            'date' => ['nullable', 'date'],
            'date_around' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*.*' => ['image'],
            'groups' => ['nullable', 'json'],
            'tags' => ['nullable', 'array'],
            'continent' => ['required'],
            'note' => ['nullable'],
            'thumbnail' => ['nullable', 'image'],
            'thumbnail_link' => ['nullable', 'string'],
            'thumbnail_base64' => ['nullable', 'string'],

            'archive' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null;
            })],
            'fond' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null;
            })],
            'folder' => ['nullable', Rule::requiredIf(function () {
                return $this->input('availability') == null;
            })],
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
                'example' => 'Private collection'
            ],
            'category_id' => [
                'description' => 'The ID of Category',
                'example' => 1,
            ],
            'subcategory_id' => [
                'description' => 'The ID of Category children',
                'example' => 2,
            ],
            'date' => [
                'description' => 'Date',
                'example' => "15.02.1789",
            ],
            'date_around' => [
                'description' => 'Date around',
                'example' => "21. century",
            ],
            'description' => [
                'description' => 'Cryptogram description',
            ],
            'used_chars' => [
                'description' => 'Used characters',
                'example' => "Used characters",
            ],
            'language_id' => [
                'description' => 'The ID of Language ',
                'example' => 1,
            ],
            'location_name' => [
                'description' => 'Location name',
            ],
            'name' => [
                'description' => 'Cryptogram name',
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
            'recipient' => [
                'description' => 'Recipient name',
                'example' => 'John Dosh',
            ],
            'sender' => [
                'description' => 'Sender name',
                'example' => 'John Sandro',
            ],
            'solution_id' => [
                'description' => 'The ID of Solution',
            ],
            'continent' => [
                'description' => 'Continent',
                'example' => 'Europe',
            ],
            'note' => [
                'description' => 'Note',
            ],
            'thumbnail' => [
                'description' => 'Thumbnail',
            ],
            'thumbnail_link' => [
                'description' => 'Thumbnail link. Used if thumbnail is empty',
            ],
            'thumbnail_base64' => [
                'description' => 'Thumbnail base64. Used if thumbnail and thumbnail_link are empty',
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

        $sanitized['thumbnail_url'] = 'temporary value';

        $sanitized['language_id'] = $sanitized['language_id'] ? $sanitized['language_id'] : null;

        $sanitized['sender_id'] = $sanitized['sender'] ? Person::firstOrCreate(['name' => $sanitized['sender']])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;
        $sanitized['recipient_id'] = $sanitized['recipient'] ? Person::firstOrCreate(['name' => $sanitized['recipient']])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;


        $sanitized['groups'] = isset($sanitized['groups']) && $sanitized['groups'] ? json_decode($sanitized['groups']) : null;
        $sanitized['created_by'] = auth()->user()->id;

        $sanitized['state'] = CipherKey::STATUS_AWAITING;


        if (isset($sanitized['location_name']) && $sanitized['location_name'] && isset($sanitized['continent']) && $sanitized['continent']) {
            $location = Location::firstOrCreate([
                'name' => $sanitized['location_name'],
                'continent' => $sanitized['continent']
            ]);
        } elseif ($sanitized['continent']) {
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
