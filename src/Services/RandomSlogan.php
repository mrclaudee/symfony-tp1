<?php

namespace App\Services;

class RandomSlogan
{
    public function getSlogan(): int
    {
        return rand(0, 100);
    }
}