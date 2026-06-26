<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use App\Repositories\Contracts\PaymentRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(
        private readonly PaymentRepository $payments,
    ) {
    }

    public function paginateForMember(string $memberId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->payments->paginateForMember($memberId, $perPage);
    }

    public function lastForMember(string $memberId, int $limit = 3): Collection
    {
        return $this->payments->lastForMember($memberId, $limit);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function recordPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            /** @var Subscription $subscription */
            $subscription = Subscription::query()
                ->with('payments')
                ->findOrFail($data['subscription_id']);

            if ($subscription->member_id !== $data['member_id']) {
                throw ValidationException::withMessages([
                    'subscription_id' => 'The selected subscription does not belong to this member.',
                ]);
            }

            $amount = round((float) $data['amount'], 2);
            $remaining = $subscription->balanceDue();

            if ($remaining <= 0.009) {
                throw ValidationException::withMessages([
                    'amount' => 'This subscription is already fully paid.',
                ]);
            }

            if ($amount > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment cannot exceed the remaining balance of $'.number_format($remaining, 2).'.',
                ]);
            }

            return $this->payments->create([
                'member_id' => $data['member_id'],
                'subscription_id' => $subscription->id,
                'amount' => $amount,
                'method' => $data['method'],
                'paid_at' => $data['paid_at'],
            ]);
        });
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function subscriptionsWithBalanceDue(string $memberId): Collection
    {
        return Subscription::query()
            ->where('member_id', $memberId)
            ->with(['plan', 'payments'])
            ->orderByDesc('start_date')
            ->get()
            ->filter(static fn (Subscription $subscription): bool => $subscription->hasBalanceDue())
            ->values();
    }
}
