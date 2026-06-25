<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-subscriptions') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $planId = $this->input('plan_id');
        if ($planId === '' || $planId === null) {
            $this->merge(['plan_id' => null]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'member_id' => ['required', 'uuid', 'exists:members,id'],
            'plan_id' => ['nullable', 'uuid', 'exists:plans,id'],
            'start_date' => ['required', 'date'],
            'discount_amount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'custom_amount' => ['nullable', 'numeric', 'min:0.01'],
            'end_date' => ['nullable', 'date'],
            'method' => ['sometimes', 'in:cash,wish-money'],
            'paid_at' => ['sometimes', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            if ($this->filled('plan_id')) {
                if ($this->filled('custom_amount')) {
                    $v->errors()->add('custom_amount', 'Do not set a custom amount when a plan is selected.');
                }
                if ($this->filled('end_date')) {
                    $v->errors()->add('end_date', 'Do not set an end date when a plan is selected; it is calculated from the plan.');
                }

                return;
            }

            if (! $this->filled('custom_amount') || (float) $this->input('custom_amount') <= 0) {
                $v->errors()->add('custom_amount', 'A custom amount greater than zero is required when no plan is selected.');
            }
            if (! $this->filled('end_date')) {
                $v->errors()->add('end_date', 'An end date is required when no plan is selected.');
            }
            if ($this->filled('end_date') && $this->filled('start_date')) {
                $end = strtotime((string) $this->input('end_date'));
                $start = strtotime((string) $this->input('start_date'));
                if ($end !== false && $start !== false && $end < $start) {
                    $v->errors()->add('end_date', 'End date must be on or after the start date.');
                }
            }
        });
    }
}
