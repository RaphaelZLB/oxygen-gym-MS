<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StorePlanWebRequest;
use App\Http\Requests\Web\UpdatePlanWebRequest;
use App\Models\Plan;
use App\Services\PlanService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class PlanController extends Controller
{
    public function __construct(
        private readonly PlanService $plans,
        private readonly SubscriptionService $subscriptions,
    ) {
    }

    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 15);
        $plans = $this->plans->paginate($perPage);

        return view('plans.index', ['plans' => $plans]);
    }

    public function create(): View
    {
        return view('plans.create');
    }

    public function store(StorePlanWebRequest $request): RedirectResponse
    {
        $this->plans->create($request->validated());

        return redirect()->route('plans.index')->with('success', 'Plan created.');
    }

    public function edit(Plan $plan): View
    {
        return view('plans.edit', ['plan' => $plan]);
    }

    public function show(Plan $plan): View
    {
        Gate::authorize('view', $plan);

        $subscriptions = $this->subscriptions->allByPlan($plan->id);

        return view('plans.show', [
            'plan' => $plan,
            'subscriptions' => $subscriptions,
        ]);
    }

    public function update(UpdatePlanWebRequest $request, Plan $plan): RedirectResponse
    {
        $this->plans->update($plan, $request->validated());

        return redirect()->route('plans.index')->with('success', 'Plan updated.');
    }
}
