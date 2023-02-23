<?php

namespace App\Http\Requests\Admin\Cryptogram;

use App\Models\Location;
use App\Models\Person;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreCryptogram extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.cryptogram.create');
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
            'category' => ['required', 'string'],
            'subcategory' => ['nullable', 'string'],
            'day' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'language' => ['required', 'string'],
            'location_name' => ['nullable', 'string'],
            'month' => ['nullable', 'integer'],
            'name' => ['required', 'string'],
            'recipient' => ['nullable', 'string'],
            'sender' => ['nullable', 'string'],
            'solution' => ['required', 'string'],
            'state_id' => ['nullable', 'string'],
            'year' => ['nullable', 'integer'],
            'flag' => ['nullable'],
            'images' => ['nullable'],
            'groups' => ['nullable'],
            'predefined_groups' => ['nullable'],
            'tags' => ['nullable'],
            'cipher_keys' => ['nullable'],
            'continent' => ['required'],
            'state' => ['required'],
            'note' => ['nullable'],
            'thumbnail' => ['nullable'],
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

        $sanitized['image_url'] = 'sdsd';

        $sanitized['language_id'] = $sanitized['language'] ? json_decode($sanitized['language'])->id : null;
        $sanitized['solution_id'] = $sanitized['solution'] ? json_decode($sanitized['solution'])->id : null;

        $sanitized['sender_id'] = $sanitized['sender'] ? json_decode($sanitized['recipient'])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;
        $sanitized['recipient_id'] = $sanitized['recipient'] ? json_decode($sanitized['recipient'])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;

        $sanitized['groups'] = $sanitized['groups'] ? json_decode($sanitized['groups']) : null;
        $sanitized['tags'] = $sanitized['tags'] ? json_decode($sanitized['tags']) : [];
        $sanitized['flag'] = $sanitized['flag'] == "false" ? false : true;
        $sanitized['created_by'] = auth()->user()->id;
        $sanitized['cipher_keys'] = $sanitized['cipher_keys'] ? json_decode($sanitized['cipher_keys']) : null;
        $sanitized['continent'] = $sanitized['continent'] ? json_decode($sanitized['continent'])->name : [];
        $sanitized['state'] = $sanitized['state'] ? json_decode($sanitized['state'])->id : [];

        $sanitized['day'] = $sanitized['day'] ?: 0;
        $sanitized['month'] = $sanitized['month'] ?: 0;
        $sanitized['year'] = $sanitized['year'] ?: 0;

        $sanitized['availability'] = $sanitized['availability'] ?: 'Unknown';

        if (isset($sanitized['location_name']) && isset($sanitized['continent'])) {
            $location = Location::firstOrCreate([
                'name' => $sanitized['location_name'],
                'continent' => $sanitized['continent']
            ]);
        } elseif (isset($sanitized['continent'])) {
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
