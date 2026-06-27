<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;

class PlanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-subscriptions');
    }

    public function view(User $user, Plan $plan): bool
    {
        return $user->can('manage-subscriptions');
    }

    public function create(User $user): bool
    {
        return $user->can('manage-subscriptions');
    }

    public function update(User $user, Plan $plan): bool
    {
        return $user->can('manage-subscriptions');
    }

    public function delete(User $user, Plan $plan): bool
    {
        return $user->can('manage-subscriptions');
    }
}

