<?php

namespace App\Kyu3;

class BattleshipValidator
{
    public function validate_battlefield(array $field): bool {
        $directions = [[0, 1], [0, -1], [1, 0], [-1, 0]];
        $crossDirections = [[1, 1], [1, -1], [-1, -1], [-1, 1]];
        $ships = [];
        $allVisited = [];
        for ($r = 0; $r < 10; $r++) {
            for ($c = 0; $c < 10; $c++) {
                if (!$field[$r][$c]) {
                    continue;
                }

                $actual = [$r, $c];

                foreach ($crossDirections as $crossDirection) {
                    $nc = [$actual[0] + $crossDirection[0], $actual[1] + $crossDirection[1]];
                    if ($nc[0] >= 0 && $nc[0] < 10 && $nc[1] >= 0 && $nc[1] < 10 && $field[$nc[0]][$nc[1]] === 1) {
                        return false;
                    }
                }

                if (in_array($actual, $allVisited, true)) {
                    continue; //if already in one of the ships found
                }

                $toVisit = [$actual];
                $visited = [];
                while(count($toVisit) > 0) {
                    $curr = array_pop($toVisit);
                    foreach ($directions as $direction) {
                        $n = [$curr[0] + $direction[0], $curr[1] + $direction[1]];
                        if ($n[0] >= 0 && $n[0] < 10 && $n[1] >= 0 && $n[1] < 10 && $field[$n[0]][$n[1]] === 1 && !in_array($n, $visited, true)) {
                            $toVisit[] = $n;
                        }
                    }
                    $visited[] = $curr;
                }

                $ship_directions = array_reduce($visited, function($neighbors, $elem) use ($actual) {
                    if ($actual === $elem) {
                        return $neighbors;
                    }
                    if ($actual[0] === $elem[0]) {
                        $neighbors[0] = 1;
                    } elseif ($actual[1] === $elem[1]) {
                        $neighbors[1] = 1;
                    }
                    return $neighbors;
                }, [0, 0]);

                if (count($visited) > 1 && !($ship_directions[0] xor $ship_directions[1])) {
                    return false; // incorrect position
                }

                $ships[] = $visited;
                $allVisited = array_merge($allVisited, $visited);
            }
        }

        $ship_count = array_reduce($ships, function($s, $ship) {
            $s[count($ship) - 1]+=1;
            return $s;
        }, [0, 0, 0, 0]);

        return $ship_count === [4, 3, 2, 1];
    }

}