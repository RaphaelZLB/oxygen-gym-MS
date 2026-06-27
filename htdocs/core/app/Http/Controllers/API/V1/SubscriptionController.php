<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Http\Responses\ApiResponse;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptions,
    ) {
    }

    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $this->authorize('create', Subscription::class);

        $subscription = $this->subscriptions->createWithInitialPayment($request->validated());

        return ApiResponse::success('Subscription created.', new SubscriptionResource($subscription), 201);
    }
}

