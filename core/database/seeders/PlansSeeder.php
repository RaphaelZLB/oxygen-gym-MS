<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => 'Daily', 'duration_days' => 1, 'price' => 3.00, 'plan_kind' => 'individual'],
            ['name' => 'Monthly', 'duration_days' => 30, 'price' => 30.00, 'plan_kind' => 'individual'],
            ['name' => 'Quarterly', 'duration_days' => 90, 'price' => 75.00, 'plan_kind' => 'individual'],
            ['name' => 'Yearly', 'duration_days' => 365, 'price' => 250.00, 'plan_kind' => 'individual'],
            ['name' => 'Couple Monthly', 'duration_days' => 30, 'price' => 55.00, 'plan_kind' => 'couple'],
        ];

        foreach ($plans as $plan) {
            Plan::query()->updateOrCreate(['name' => $plan['name']], $plan);
        }
    }
}

