<?php

declare(strict_types=1);

namespace App\Roboscript;

enum Direction: string {
    case UP = "U";
    case DOWN = "D";
    case RIGHT = "R";
    case LEFT = "L"; 

    public function goRight() 
    {
        return match ($this) {
            self::UP => self::RIGHT,
            self::RIGHT => self::DOWN,
            self::DOWN => self::LEFT,
            self::LEFT => self::UP,
        };
    }

    public function goLeft() 
    {
        return match ($this) {
            self::UP => self::LEFT,
            self::RIGHT => self::UP,
            self::DOWN => self::RIGHT,
            self::LEFT => self::DOWN,
        };
    }

    public function nextPosition(Position $p): Position 
    {
        return match($this) {
            self::UP => new Position($p->x, $p->y - 1),
            self::RIGHT => new Position($p->x + 1, $p->y),
            self::DOWN => new Position($p->x, $p->y + 1),
            self::LEFT => new Position($p->x - 1, $p->y),
        };
    }
    
}
