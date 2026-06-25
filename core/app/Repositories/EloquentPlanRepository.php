<?php

namespace App\Repositories;

use App\Models\Plan;
use App\Repositories\Contracts\PlanRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPlanRepository implements PlanRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Plan::query()->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Plan
    {
        return Plan::create($data);
    }

    public function update(Plan $plan, array $data): Plan
    {
        $plan->fill($data);
        $plan->save();

        return $plan;
    }

    public function delete(Plan $plan): void
    {
        $plan->delete();
    }
}

