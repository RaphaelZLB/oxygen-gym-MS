<?php

namespace App\Providers;

use App\Repositories\Contracts\MemberRepository;
use App\Repositories\Contracts\PaymentRepository;
use App\Repositories\Contracts\PlanRepository;
use App\Repositories\Contracts\SubscriptionRepository;
use App\Repositories\EloquentMemberRepository;
use App\Repositories\EloquentPaymentRepository;
use App\Repositories\EloquentPlanRepository;
use App\Repositories\EloquentSubscriptionRepository;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Policies\MemberPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PlanPolicy;
use App\Policies\SubscriptionPolicy;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MemberRepository::class, EloquentMemberRepository::class);
        $this->app->bind(PlanRepository::class, EloquentPlanRepository::class);
        $this->app->bind(SubscriptionRepository::class, EloquentSubscriptionRepository::class);
        $this->app->bind(PaymentRepository::class, EloquentPaymentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(static function (User $user, string $ability) {
            return $user->hasRole('Admin') ? true : null;
        });

        Gate::policy(Member::class, MemberPolicy::class);
        Gate::policy(Plan::class, PlanPolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
    }
}
