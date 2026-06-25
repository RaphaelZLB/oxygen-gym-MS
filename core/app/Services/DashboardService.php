<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Carbon;

class DashboardService
{
    /**
     * @return array<string, int|float>
     */
    public function stats(): array
    {
        $totalMembers = Member::query()->count();

        $activeSubscriptions = Subscription::query()->where('status', 'active')->count();
        $expiredSubscriptions = Subscription::query()->where('status', 'expired')->count();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $revenueThisMonth = (float) Payment::query()
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        return [
            'total_members' => $totalMembers,
            'active_subscriptions' => $activeSubscriptions,
            'expired_subscriptions' => $expiredSubscriptions,
            'revenue_this_month' => $revenueThisMonth,
        ];
    }
}

