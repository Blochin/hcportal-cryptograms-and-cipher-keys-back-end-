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
            'availability' => ['nullable', 'string'],
            'category_id' => ['required', 'string', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'string', 'exists:categories,id'],
            'day' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'language_id' => ['required', 'integer', 'exists:languages,id'],
            'location_name' => ['nullable', 'string'],
            'month' => ['nullable', 'integer'],
            'name' => ['required', 'string'],
            'recipient' => ['nullable', 'string'],
            'sender' => ['nullable', 'string'],
            'solution_id' => ['required', 'integer', 'exists:solutions,id'],
            'year' => ['nullable', 'integer'],
            'before_crist' => ['required', 'boolean'],
            'images' => ['nullable', 'array'],
            'groups' => ['nullable', 'json'],
            'tags' => ['nullable', 'array'],
            'continent' => ['required'],
            'note' => ['nullable'],
            'thumbnail' => ['nullable', 'image'],
            'thumbnail_link' => ['nullable', 'string'],
            'thumbnail_base64' => ['nullable', 'text'],
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
            'category_id' => [
                'description' => 'The ID of Category',
                'example' => 1,
            ],
            'subcategory_id' => [
                'description' => 'The ID of Category children',
                'example' => 2,
            ],
            'day' => [
                'description' => 'Day',
                'example' => 1,
            ],
            'description' => [
                'description' => 'Cryptogram description',
            ],
            'language_id' => [
                'description' => 'The ID of Language ',
                'example' => 1,
            ],
            'location_name' => [
                'description' => 'Location name',
            ],
            'month' => [
                'description' => 'Month',
                'example' => 10,
            ],
            'name' => [
                'description' => 'Cryptogram name',
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
            'year' => [
                'description' => 'Year',
                'example' => 1998,
            ],
            'before_crist' => [
                'description' => 'Before crist',
                'example' => 1,
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

        $sanitized['image_url'] = 'temporary value';

        $sanitized['language_id'] = $sanitized['language'] ? Language::firstOrCreate(['name' => $sanitized['language']])->id : null;

        $sanitized['sender_id'] = $sanitized['sender'] ? Person::firstOrCreate(['name' => $sanitized['sender']])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;
        $sanitized['recipient_id'] = $sanitized['recipient'] ? Person::firstOrCreate(['name' => $sanitized['recipient']])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;


        $sanitized['groups'] = isset($sanitized['groups']) && $sanitized['groups'] ? json_decode($sanitized['groups']) : null;
        $sanitized['flag'] = $sanitized['before_crist'] == "false" || $sanitized['before_crist'] == "0" ? false : true;
        $sanitized['created_by'] = auth()->user()->id;

        $sanitized['state'] = CipherKey::STATUS_AWAITING;

        $sanitized['day'] = $sanitized['day'] ?: 0;
        $sanitized['month'] = $sanitized['month'] ?: 0;
        $sanitized['year'] = $sanitized['year'] ?: 0;
        $sanitized['availability'] =  $sanitized['availability'] ?: 'Unknown';

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
