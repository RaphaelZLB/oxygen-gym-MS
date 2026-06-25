<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function create(User $user): bool
    {
        return $user->can('manage-subscriptions');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('manage-subscriptions');
    }

    public function view(User $user, Subscription $subscription): bool
    {
        return $user->can('manage-subscriptions');
    }
}

