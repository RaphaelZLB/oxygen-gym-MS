<?php

namespace App\Http\Requests;

use App\Models\Subscription;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-payments') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subscription_id' => ['required', 'uuid', 'exists:subscriptions,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'in:cash,wish-money'],
            'paid_at' => ['required', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $member = $this->route('member');
            $subscriptionId = $this->input('subscription_id');

            if (! $member || ! $subscriptionId) {
                return;
            }

            /** @var Subscription|null $subscription */
            $subscription = Subscription::query()
                ->with('payments')
                ->find($subscriptionId);

            if (! $subscription) {
                return;
            }

            if ($subscription->member_id !== $member->id) {
                $v->errors()->add('subscription_id', 'The selected subscription does not belong to this member.');
            }
        });
    }
}
