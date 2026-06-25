<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\MemberController;
use App\Http\Controllers\API\V1\PaymentController;
use App\Http\Controllers\API\V1\PlanController;
use App\Http\Controllers\API\V1\SubscriptionController;
use App\Http\Controllers\API\V1\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('users', [UserManagementController::class, 'store'])->middleware('permission:manage-users');

        // Prefix API resource route names to avoid collisions with Web route names.
        Route::apiResource('members', MemberController::class)
            ->middleware('permission:manage-members')
            ->names('api.members');

        Route::apiResource('plans', PlanController::class)
            ->middleware('permission:manage-subscriptions')
            ->names('api.plans');

        Route::post('subscriptions', [SubscriptionController::class, 'store'])->middleware('permission:manage-subscriptions');

        Route::get('payments', [PaymentController::class, 'index'])->middleware('permission:manage-payments');
    });
});

