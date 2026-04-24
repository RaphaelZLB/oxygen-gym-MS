<?php

namespace App\Repositories\Contracts;

use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PaymentRepository
{
    public function paginateForMember(string $memberId, int $perPage = 15): LengthAwarePaginator;

    public function lastForMember(string $memberId, int $limit = 3): Collection;

    public function create(array $data): Payment;
}

