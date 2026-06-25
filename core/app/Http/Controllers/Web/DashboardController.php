<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\SubscriptionService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
        private readonly SubscriptionService $subscriptions,
    ) {
    }

    public function __invoke(): View
    {
        $stats = $this->dashboard->stats();
        $expiringSoon = $this->subscriptions->expiringWithinDays(3, 5);

        return view('dashboard.index', [
            'stats' => $stats,
            'expiringSoon' => $expiringSoon,
        ]);
    }
}
