<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-members') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tags' => $this->has('tags') ? (array) $this->input('tags') : [],
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:50',
                Rule::unique('members', 'phone')->whereNull('deleted_at'),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'status' => ['sometimes', 'in:active,inactive,frozen'],
            'medical_notes' => ['nullable', 'string'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['string', Rule::in(['VIP', 'Athlete', 'Intermediate', 'Beginner'])],
            'training_time' => ['nullable', 'in:AM,PM'],
            'next_step' => ['sometimes', 'nullable', 'in:subscription'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            if ($this->input('next_step') === 'subscription' && $this->input('status') !== 'active') {
                $v->errors()->add(
                    'next_step',
                    'Create & add payment is only available when membership status is Active.',
                );
            }
        });
    }
}
