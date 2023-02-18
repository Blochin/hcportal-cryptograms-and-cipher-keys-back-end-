<?php

namespace App\Http\Requests\Admin\Cryptogram;

use App\Models\Location;
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
            'availability' => ['required', 'string'],
            'category' => ['required', 'string'],
            'subcategory' => ['nullable', 'string'],
            'day' => ['required', 'integer'],
            'description' => ['nullable', 'string'],
            'language' => ['required', 'string'],
            'location_name' => ['nullable', 'string'],
            'month' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'recipient' => ['required', 'string'],
            'sender' => ['required', 'string'],
            'solution' => ['required', 'string'],
            'state_id' => ['nullable', 'string'],
            'year' => ['required', 'integer'],
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
        $sanitized['recipient_id'] = $sanitized['recipient'] ? json_decode($sanitized['recipient'])->id : null;
        $sanitized['sender_id'] = $sanitized['sender'] ? json_decode($sanitized['sender'])->id : null;
        $sanitized['groups'] = $sanitized['groups'] ? json_decode($sanitized['groups']) : null;
        $sanitized['tags'] = $sanitized['tags'] ? json_decode($sanitized['tags']) : [];
        $sanitized['flag'] = $sanitized['flag'] == "false" ? false : true;
        $sanitized['created_by'] = auth()->user()->id;
        $sanitized['cipher_keys'] = $sanitized['cipher_keys'] ? json_decode($sanitized['cipher_keys']) : null;
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
