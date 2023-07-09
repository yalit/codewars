<?php

declare(strict_types=1);

namespace App\Roboscript;

class Direction {
    public const UP = "U";
    public const DOWN = "D";
    public const RIGHT = "R";
    public const LEFT = "L"; 

    public function __construct(public string $direction)
    {
    }

    public function goRight(): self
    {
        return match ($this->direction) {
            self::UP => new self(self::RIGHT),
            self::RIGHT => new self(self::DOWN),
            self::DOWN => new self(self::LEFT),
            self::LEFT => new self(self::UP),
        };
    }

    public function goLeft(): self
    {
        return match ($this->direction) {
            self::UP => new self(self::LEFT),
            self::RIGHT => new self(self::UP),
            self::DOWN => new self(self::RIGHT),
            self::LEFT => new self(self::DOWN),
        };
    }

    public function nextPosition(array $p): array 
    {
        return match($this->direction) {
            self::UP => [$p[0], $p[1] - 1],
            self::RIGHT => [$p[0] + 1, $p[1]],
            self::DOWN => [$p[0], $p[1] + 1],
            self::LEFT => [$p[0] - 1, $p[1]],
        };
    }
}
