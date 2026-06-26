<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Plan;
use App\Models\Subscription;
use App\Repositories\Contracts\PaymentRepository;
use App\Repositories\Contracts\SubscriptionRepository;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function __construct(
        private readonly SubscriptionRepository $subscriptions,
        private readonly PaymentRepository $payments,
    ) {
    }

    public function createWithInitialPayment(array $data): Subscription
    {
        return DB::transaction(function () use ($data) {
            /** @var Member $member */
            $member = Member::query()->findOrFail($data['member_id']);

            if (! empty($data['plan_id'])) {
                return $this->createFromPlan($member, $data);
            }

            return $this->createCustom($member, $data);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createFromPlan(Member $member, array $data): Subscription
    {
        /** @var Plan $plan */
        $plan = Plan::query()->findOrFail($data['plan_id']);

        $start = CarbonImmutable::parse($data['start_date'])->startOfDay();
        // Inclusive calendar days: N days = start through start + (N - 1); N=1 is same calendar day.
        $end = $start->addDays(max(0, (int) $plan->duration_days - 1));
        $status = now()->startOfDay()->lte($end) ? 'active' : 'expired';

        $planPrice = (float) $plan->price;
        $discountPercent = isset($data['discount_amount']) ? (float) $data['discount_amount'] : 0.0;
        if ($discountPercent < 0) {
            $discountPercent = 0.0;
        }
        if ($discountPercent > 100) {
            $discountPercent = 100.0;
        }

        $finalPrice = round($planPrice * (1 - $discountPercent / 100), 2);
        if ($finalPrice <= 0) {
            throw new \InvalidArgumentException('Final price must be greater than 0 after discount.');
        }

        $isRenewal = Subscription::query()
            ->where('member_id', $member->id)
            ->exists();

        $subscription = $this->subscriptions->create([
            'member_id' => $member->id,
            'plan_id' => $plan->id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'status' => $status,
            'is_renewal' => $isRenewal,
            'discount_amount' => $discountPercent,
            'final_price' => $finalPrice,
        ]);

        $paidAt = isset($data['paid_at']) ? CarbonImmutable::parse($data['paid_at']) : now();
        $paymentAmount = $this->resolveInitialPaymentAmount($data, $finalPrice);

        $this->payments->create([
            'member_id' => $member->id,
            'subscription_id' => $subscription->id,
            'amount' => $paymentAmount,
            'method' => $data['method'] ?? 'cash',
            'paid_at' => $paidAt,
        ]);

        return $subscription->fresh(['member', 'plan', 'payments']);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createCustom(Member $member, array $data): Subscription
    {
        $start = CarbonImmutable::parse($data['start_date'])->startOfDay();
        $end = CarbonImmutable::parse($data['end_date'])->startOfDay();
        if ($end->lt($start)) {
            throw new \InvalidArgumentException('End date must be on or after the start date.');
        }

        $status = now()->startOfDay()->lte($end) ? 'active' : 'expired';
        $finalPrice = round((float) $data['custom_amount'], 2);
        if ($finalPrice <= 0) {
            throw new \InvalidArgumentException('Custom amount must be greater than 0.');
        }

        $isRenewal = Subscription::query()
            ->where('member_id', $member->id)
            ->exists();

        $subscription = $this->subscriptions->create([
            'member_id' => $member->id,
            'plan_id' => null,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'status' => $status,
            'is_renewal' => $isRenewal,
            'discount_amount' => 0,
            'final_price' => $finalPrice,
        ]);

        $paidAt = isset($data['paid_at']) ? CarbonImmutable::parse($data['paid_at']) : now();
        $paymentAmount = $this->resolveInitialPaymentAmount($data, $finalPrice);

        $this->payments->create([
            'member_id' => $member->id,
            'subscription_id' => $subscription->id,
            'amount' => $paymentAmount,
            'method' => $data['method'] ?? 'cash',
            'paid_at' => $paidAt,
        ]);

        return $subscription->fresh(['member', 'plan', 'payments']);
    }

    public function expiringWithinDays(int $days = 3, int $perPage = 15): LengthAwarePaginator
    {
        $today = now()->startOfDay();
        $threshold = $today->copy()->addDays($days)->toDateString();

        return Subscription::query()
            ->where('status', 'active')
            ->whereDate('end_date', '>=', $today->toDateString())
            ->whereDate('end_date', '<=', $threshold)
            ->with(['member', 'plan'])
            ->orderBy('end_date')
            ->paginate($perPage);
    }

    public function allByMember(string $memberId): Collection
    {
        return $this->subscriptions->allByMember($memberId);
    }

    public function allByPlan(string $planId): Collection
    {
        return $this->subscriptions->allByPlan($planId);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveInitialPaymentAmount(array $data, float $finalPrice): float
    {
        if (! array_key_exists('payment_amount', $data) || $data['payment_amount'] === null || $data['payment_amount'] === '') {
            return $finalPrice;
        }

        $paymentAmount = round((float) $data['payment_amount'], 2);

        if ($paymentAmount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be greater than 0.');
        }

        if ($paymentAmount > $finalPrice) {
            throw new \InvalidArgumentException('Payment amount cannot exceed the subscription total.');
        }

        return $paymentAmount;
    }
}
