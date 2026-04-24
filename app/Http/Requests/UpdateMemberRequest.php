<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-members') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    protected function prepareForValidation(): void
    {
        if ($this->boolean('tags_submitted')) {
            $this->merge([
                'tags' => (array) $this->input('tags', []),
            ]);
        } elseif ($this->exists('tags')) {
            $this->merge([
                'tags' => (array) $this->input('tags'),
            ]);
        }
    }

    public function rules(): array
    {
        $memberId = $this->route('member')?->id ?? $this->route('member');

        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'phone' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('members', 'phone')
                    ->whereNull('deleted_at')
                    ->ignore($memberId),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'status' => ['sometimes', 'in:active,inactive,frozen'],
            'medical_notes' => ['nullable', 'string'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['string', Rule::in(['VIP', 'Athlete', 'Intermediate', 'Beginner'])],
            'training_time' => ['nullable', 'in:AM,PM'],
        ];
    }
}
