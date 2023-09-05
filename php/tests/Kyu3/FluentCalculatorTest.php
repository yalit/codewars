<?php

namespace Tests\Kyu3;

use PHPUnit\Framework\TestCase;
use App\Kyu3\FluentCalculator\FluentCalculator;

class FluentCalculatorTest extends TestCase
{
    public function testInit() {
        $this->assertSame(FluentCalculator::init()->zero(), 0);
    }
}