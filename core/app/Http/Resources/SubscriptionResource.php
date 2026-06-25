<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'plan_id' => $this->plan_id,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'status' => $this->status,
            'is_renewal' => (bool) $this->is_renewal,
            'discount_amount' => $this->discount_amount,
            'final_price' => $this->final_price,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'member' => $this->whenLoaded('member', fn () => new MemberResource($this->member)),
            'plan' => $this->whenLoaded('plan', fn () => $this->plan ? new PlanResource($this->plan) : null),
            'payments' => $this->whenLoaded('payments', fn () => PaymentResource::collection($this->payments)),
        ];
    }
}

