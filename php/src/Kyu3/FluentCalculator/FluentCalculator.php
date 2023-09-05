<?php

namespace App\Kyu3\FluentCalculator;

class FluentCalculator
{
    private int $value = 0;
    public FluentCalculator $zero;
    public FluentCalculator $one;
    public FluentCalculator $two;
    public FluentCalculator $three;
    public FluentCalculator $four;
    public FluentCalculator $five;
    public FluentCalculator $six;
    public FluentCalculator $seven;
    public FluentCalculator $eight;
    public FluentCalculator $nine;
    public FluentCalculator $plus;
    public FluentCalculator $minus;
    public FluentCalculator $times;
    public FluentCalculator $dividedBy;

    public static function init(): self {
        $fl = new self();
        $fl->one = new self();
        $fl->two = new self();
        $fl->three = new self();
        $fl->four = new self();
        $fl->five = new self();
        $fl->six = new self();
        $fl->seven = new self();
        $fl->eight = new self();
        $fl->nine = new self();
        $fl->minus = new self();
        $fl->plus = new self();
        $fl->times = new self();
        $fl->dividedBy = new self();

        return $fl;
    }

    public function __invoke()
    {
        return $this->value;
    }
}

