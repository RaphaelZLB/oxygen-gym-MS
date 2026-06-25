<?php

namespace App\Services;

use App\Models\Plan;
use App\Repositories\Contracts\PlanRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PlanService
{
    public function __construct(
        private readonly PlanRepository $plans,
    ) {
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->plans->paginate($perPage);
    }

    public function create(array $data): Plan
    {
        return $this->plans->create($data);
    }

    public function update(Plan $plan, array $data): Plan
    {
        return $this->plans->update($plan, $data);
    }

    public function delete(Plan $plan): void
    {
        $this->plans->delete($plan);
    }
}

