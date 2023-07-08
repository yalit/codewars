<?php

declare(strict_types=1);

namespace App\Roboscript;

class Instruction
{
    public const FORWARD = "F";
    public const LEFT = "L";
    public const RIGHT = "R";

    public function __construct(private string $instruction, private int $nb = 1)
    {
    }

    /**
     * 
     * @return string[] 
     */
    public static function allInstructions(): array
    {
        return [self::FORWARD, self::LEFT, self::RIGHT];
    }

    /**
     * 
     * @return Position[] 
     */
    public function execute(Position $position): array
    {
        $positions = [];
        for ($a = 0; $a < $this->nb; $a++) {
            $position = match($this->instruction) {
                self::FORWARD => $position->direction->nextPosition($position),
                self::LEFT => new Position($position->x, $position->y, $position->direction->goLeft()),
                self::RIGHT => new Position($position->x, $position->y, $position->direction->goRight())
            };
            $positions[] = $position;
        }

        return $positions;
    }

    public function size(): int 
    {
        return \strlen($this->instruction) + ($this->nb === 1 ? 0 : \strlen((string)$this->nb));
    }
}