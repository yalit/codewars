<?php

declare(strict_types=1);

namespace Tests;

use App\Kyu4\SumIntervals;
use PHPUnit\Framework\TestCase;

final class SumIntervalsTest extends TestCase
{
    public function testExamples() {
        // Non-overlapping intervals
        $this->assertEquals(4, SumIntervals::sum_intervals([[1, 5]]));
        $this->assertEquals(8, SumIntervals::sum_intervals([
          [1, 5],
          [6, 10]
        ]));
        // Overlapping intervals
        $this->assertEquals(4, SumIntervals::sum_intervals([
          [1, 5],
          [1, 5]
        ]));
        $this->assertEquals(7, SumIntervals::sum_intervals([
          [1, 4],
          [7, 10],
          [3, 5]
        ]));
      }
      
      public function testLargeIntervals() {
        $this->assertEquals(2e9, SumIntervals::sum_intervals([[-1e9, 1e9]]));
        $this->assertEquals(1e8 + 30, SumIntervals::sum_intervals([
          [0, 20],
          [-1e8, 10],
          [30, 40]
        ]));
      }
}
