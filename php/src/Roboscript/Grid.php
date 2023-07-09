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
                $grid[$y - $minMax['height']['min']] .= \in_array([$x, $y], $this->positions) ? "*" : " ";
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

        return array_reduce($this->positions, fn($mm, array $pos) => [
            'width' => ['min' => min($mm['width']['min'], $pos[0]), 'max' => max($mm['width']['max'], $pos[0])],
            'height' => ['min' => min($mm['height']['min'], $pos[1]), 'max' => max($mm['height']['max'], $pos[1])]
        ], $base);
    }
}