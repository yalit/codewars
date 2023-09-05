<?php

namespace App\Kyu3\FluentCalculator;

class Operation
{
    private int $value = 0;

    public function __invoke(): int
    {
        return $this->value;
    }

}