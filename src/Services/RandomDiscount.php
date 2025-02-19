<?php

namespace App\Services;

class RandomDiscount
{
    private int $min;
    private int $max;
    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function generate(): int
    {
        return rand($this->min, $this->max);
    }
}