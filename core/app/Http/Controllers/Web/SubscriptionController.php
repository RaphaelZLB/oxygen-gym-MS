<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Member;
use App\Models\Plan;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptions,
    ) {
    }

    public function create(): View
    {
        return view('subscriptions.create', [
            'members' => Member::query()->orderBy('first_name')->orderBy('last_name')->get(),
            'plans' => Plan::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        $this->subscriptions->createWithInitialPayment($request->validated());

        return redirect()->route('subscriptions.create')->with('success', 'Subscription created.');
    }
}
