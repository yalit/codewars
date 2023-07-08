<?php

declare(strict_types=1);

namespace App\Roboscript;

final class Roboscript 
{
    public static function highlight(string $code): string
    {
        return \preg_replace(
            [
                '/(F+)/',
                '/(L+)/',
                '/(R+)/',
                '/([0-9]+)/'
            ],
            [
                '<span style="color: pink">$1</span>',
                '<span style="color: red">$1</span>',
                '<span style="color: green">$1</span>',
                '<span style="color: orange">$1</span>',
            ],
            $code
        );
    }
}