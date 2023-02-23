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
            'image' => ['nullable'],
            'groups' => ['nullable'],
            // 'predefined_groups' => ['nullable'],
            'tags' => ['nullable'],
            'cipher_keys' => ['nullable'],
            'continent' => ['required'],
            'state' => ['required'],
            'note' => ['nullable'],
            'note_new' => ['nullable'],
            'thumbnail' => ['nullable']

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
        $sanitized['sender_id'] = $sanitized['sender'] ? Person::firstOrCreate(['name' => $sanitized['']])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;
        $sanitized['recipient_id'] = $sanitized['recipient'] ? Person::firstOrCreate(['name' => $sanitized['recipient']])->id : Person::firstOrCreate(['name' => 'Unknown'])->id;

        $sanitized['groups'] = $sanitized['groups'] ? json_decode($sanitized['groups']) : null;

        $sanitized['tags'] = $sanitized['tags'] ? json_decode($sanitized['tags']) : [];
        $sanitized['flag'] = $sanitized['flag'] == "false" ? false : true;
        $sanitized['cipher_keys'] = $sanitized['cipher_keys'] ? json_decode($sanitized['cipher_keys']) : null;

        $sanitized['day'] = $sanitized['day'] ?: 0;
        $sanitized['month'] = $sanitized['month'] ?: 0;
        $sanitized['year'] = $sanitized['year'] ?: 0;

        $sanitized['availability'] = $sanitized['availability'] ?: 'Unknown';

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

        //dd($sanitized);

        return $sanitized;
    }
}
