<?php

namespace App\Http\Requests\Admin\Cryptogram;

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
            'day' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'language' => ['required', 'string'],
            'location' => ['required', 'string'],
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
        $sanitized['category_id'] = $sanitized['category'] ? json_decode($sanitized['category'])->id : null;
        $sanitized['language_id'] = $sanitized['language'] ? json_decode($sanitized['language'])->id : null;
        $sanitized['solution_id'] = $sanitized['solution'] ? json_decode($sanitized['solution'])->id : null;
        $sanitized['location_id'] = $sanitized['location'] ? json_decode($sanitized['location'])->id : null;
        $sanitized['recipient_id'] = $sanitized['recipient'] ? json_decode($sanitized['recipient'])->id : null;
        $sanitized['sender_id'] = $sanitized['sender'] ? json_decode($sanitized['sender'])->id : null;
        $sanitized['groups'] = $sanitized['groups'] ? json_decode($sanitized['groups']) : null;
        $sanitized['predefined_groups'] = $sanitized['predefined_groups'] ? json_decode($sanitized['predefined_groups']) : null;
        $sanitized['tags'] = $sanitized['tags'] ? json_decode($sanitized['tags']) : [];
        $sanitized['flag'] = $sanitized['flag'] == "false" ? false : true;
        $sanitized['created_by'] = auth()->user()->id;
        $sanitized['cipher_keys'] = $sanitized['cipher_keys'] ? json_decode($sanitized['cipher_keys']) : null;

        $state = State::create([
            'name' => $sanitized['name'],
            'state' => State::STATUS_NEW,
            'created_by' => auth()->user()->id
        ]);

        $sanitized['state_id'] = $state->id;


        return $sanitized;
    }
}
