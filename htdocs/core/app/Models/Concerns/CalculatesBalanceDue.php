<?php

namespace App\Models\Concerns;

trait CalculatesBalanceDue
{
    public function amountPaid(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->sum('amount');
        }

        return (float) $this->payments()->sum('amount');
    }

    public function balanceDue(): float
    {
        if ($this->final_price === null) {
            return 0.0;
        }

        return max(0, round((float) $this->final_price - $this->amountPaid(), 2));
    }

    public function hasBalanceDue(): bool
    {
        return $this->balanceDue() > 0.009;
    }
}
