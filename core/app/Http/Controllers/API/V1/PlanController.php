<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Http\Responses\ApiResponse;
use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PlanController extends Controller
{
    public function __construct(
        private readonly PlanService $plans,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Plan::class);

        $perPage = (int) $request->query('per_page', 15);
        $result = $this->plans->paginate($perPage);

        $payload = PlanResource::collection($result)->response()->getData(true);

        return ApiResponse::success('', $payload);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Plan::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:plans,name'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'plan_kind' => ['sometimes', 'in:individual,couple'],
        ]);
        if (! isset($data['plan_kind'])) {
            $data['plan_kind'] = 'individual';
        }

        $plan = $this->plans->create($data);

        return ApiResponse::success('Plan created.', new PlanResource($plan), 201);
    }

    public function show(Plan $plan): JsonResponse
    {
        $this->authorize('view', $plan);

        return ApiResponse::success('', new PlanResource($plan));
    }

    public function update(Request $request, Plan $plan): JsonResponse
    {
        $this->authorize('update', $plan);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'duration_days' => ['sometimes', 'integer', 'min:1'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'plan_kind' => ['sometimes', 'in:individual,couple'],
        ]);

        if (isset($data['name']) && $data['name'] !== $plan->name) {
            $exists = Plan::query()->where('name', $data['name'])->whereKeyNot($plan->id)->exists();
            if ($exists) {
                throw ValidationException::withMessages(['name' => ['The name has already been taken.']]);
            }
        }

        $plan = $this->plans->update($plan, $data);

        return ApiResponse::success('Plan updated.', new PlanResource($plan));
    }

    public function destroy(Plan $plan): JsonResponse
    {
        $this->authorize('delete', $plan);

        $this->plans->delete($plan);

        return ApiResponse::success('Plan deleted.');
    }
}

