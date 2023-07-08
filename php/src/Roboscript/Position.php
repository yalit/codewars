<?php

declare(strict_types=1);

namespace App\Roboscript;

final class Position 
{
    public function __construct(public int $x, public int $y, public Direction $direction)
    {
    }
}