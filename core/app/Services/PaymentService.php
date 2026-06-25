<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentService
{
    public function __construct(
        private readonly PaymentRepository $payments,
    ) {
    }

    public function paginateForMember(string $memberId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->payments->paginateForMember($memberId, $perPage);
    }

    public function lastForMember(string $memberId, int $limit = 3): Collection
    {
        return $this->payments->lastForMember($memberId, $limit);
    }
}

