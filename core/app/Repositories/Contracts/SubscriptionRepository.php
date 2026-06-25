<?php

namespace App\Repositories\Contracts;

use App\Models\Subscription;
use Illuminate\Support\Collection;

interface SubscriptionRepository
{
    public function create(array $data): Subscription;

    public function allByMember(string $memberId): Collection;

    public function allByPlan(string $planId): Collection;
}

