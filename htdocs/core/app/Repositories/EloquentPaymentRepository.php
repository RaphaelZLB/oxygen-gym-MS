<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPaymentRepository implements PaymentRepository
{
    public function paginateForMember(string $memberId, int $perPage = 15): LengthAwarePaginator
    {
        return Payment::query()
            ->where('member_id', $memberId)
            ->with(['subscription.plan'])
            ->orderByDesc('paid_at')
            ->paginate($perPage);
    }

    public function lastForMember(string $memberId, int $limit = 3): Collection
    {
        return Payment::query()
            ->where('member_id', $memberId)
            ->orderByDesc('paid_at')
            ->with(['subscription.plan'])
            ->limit($limit)
            ->get();
    }

    public function create(array $data): Payment
    {
        return Payment::create($data);
    }
}

