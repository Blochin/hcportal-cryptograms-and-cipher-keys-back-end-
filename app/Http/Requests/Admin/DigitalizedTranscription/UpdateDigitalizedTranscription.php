<?php

namespace App\Http\Requests\Admin\DigitalizedTranscription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateDigitalizedTranscription extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.digitalized-transcription.edit', $this->digitalizedTranscription);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'cipher_key' => ['required', 'array'],
            'digitalized_version' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'digitalization_date' => ['nullable', 'date'],
            'encryption_pairs' => ['required', 'array']

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

        $sanitized['cipher_key_id'] = $sanitized['cipher_key']['id'];
        $sanitized['created_by'] = auth()->user()->id;

        return $sanitized;
    }
}
