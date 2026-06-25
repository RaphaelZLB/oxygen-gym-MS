<?php

namespace App\Repositories;

use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepository;
use Illuminate\Support\Collection;

class EloquentSubscriptionRepository implements SubscriptionRepository
{
    public function create(array $data): Subscription
    {
        return Subscription::create($data);
    }

    public function allByMember(string $memberId): Collection
    {
        return Subscription::query()
            ->where('member_id', $memberId)
            ->with(['plan', 'payments'])
            ->orderByDesc('start_date')
            ->get();
    }

    public function allByPlan(string $planId): Collection
    {
        return Subscription::query()
            ->where('plan_id', $planId)
            ->with(['member'])
            ->orderByDesc('start_date')
            ->get();
    }
}

