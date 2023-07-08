<?php

declare(strict_types=1);

namespace App\Roboscript;

final class Grid
{
    /**
     * 
     * @param Position[] $positions 
     */
    public function __construct(private array $positions)
    { 
    }

    public function display(): string
    {
        $minMax = $this->getMinMax();
        $grid = [];
        for ($y = $minMax['height']['min']; $y <= $minMax['height']['max']; $y++) {
            $grid[] = "";
            for ($x = $minMax['width']['min']; $x <= $minMax['width']['max']; $x++) {
                $grid[$y - $minMax['height']['min']] .= static::isInPositions($this->positions, new Position($x, $y)) ? "*" : " ";
            }
        }

        return join("\r\n", $grid);
    }

    /**
     * @return {'width': {'min': int, 'max':int}, 'height': {'min': int, 'max':int}}
     */
    private function getMinMax(): array
    {
        $base = [
            'width' => ['min' => 0, 'max' => 0],
            'height' => ['min' => 0, 'max' => 0]
        ];

        return array_reduce($this->positions, fn($mm, Position $pos) => [
            'width' => ['min' => min($mm['width']['min'], $pos->x), 'max' => max($mm['width']['max'], $pos->x)],
            'height' => ['min' => min($mm['height']['min'], $pos->y), 'max' => max($mm['height']['max'], $pos->y)]
        ], $base);
    }

    /**
     * 
     * @param Position[] $positions 
     */
    private static function isInPositions(array $positions, Position $position): bool 
    {
        foreach($positions as $pos) {
            if ($position->x === $pos->x && $position->y === $pos->y) {
                return true;
            }
        }
        return false;
    }
}