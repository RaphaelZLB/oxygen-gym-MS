<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Http\Responses\ApiResponse;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $payments,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Payment::class);

        $data = $request->validate([
            'member_id' => ['required', 'uuid', 'exists:members,id'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $memberId = $data['member_id'];
        $perPage = (int) $request->query('per_page', 15);

        $result = $this->payments->paginateForMember($memberId, $perPage);

        $payload = PaymentResource::collection($result)->response()->getData(true);

        return ApiResponse::success('', $payload);
    }
}

