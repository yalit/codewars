<?php

declare(strict_types=1);

namespace App\Kyu4;

class SumIntervals
{
    public static function sum_intervals(array $intervals): int 
    {
        \usort($intervals, fn(array $i1, array $i2) => $i1[0] >= $i2[0]);

        $toSum = [$intervals[0]];
        $t = 0;
        $i = 1;
        while ($i < count($intervals)) {
            if ($intervals[$i][0] > $toSum[$t][1]) {
                $toSum[] = $intervals[$i];
                $t++;
            } else {
                $toSum[$t] = [$toSum[$t][0], \max($toSum[$t][1], $intervals[$i][1])];
            }

            $i++;
        }

        return (int)\array_reduce($toSum, fn(int $sum, $interval) => $sum + ($interval[1] - $interval[0]), 0);
    }
}