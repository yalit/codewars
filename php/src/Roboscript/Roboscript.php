<?php

declare(strict_types=1);

namespace App\Roboscript;

use Exception;

/**
 * Implementing solutions for the series of Kata on Codewards Roboscript starting here : https://www.codewars.com/kata/roboscript-number-1-implement-syntax-highlighting
 */
final class Roboscript 
{
    public static function highlight(string $code): string
    {
        return \preg_replace(
            ['/(F+)/', '/(L+)/', '/(R+)/', '/([0-9]+)/'],
            ['<span style="color: pink">$1</span>', '<span style="color: red">$1</span>', '<span style="color: green">$1</span>', '<span style="color: orange">$1</span>',],
            $code
        );
    }

    public static function execute(string $code): string
    {
        //initialize
        /** @var Position[] $positions */
        $positions = [new Position(0, 0, Direction::RIGHT)];

        $i = 0;
        $execCode = str_split($code);
        while($i < count($execCode)) {
            $instruction = InstructionParser::getNextInstruction(\array_slice($execCode, $i));
            $positions = \array_merge($positions, $instruction->execute($positions[count($positions) - 1]));
            $i += $instruction->size();
        }

        return (new Grid($positions))->display();
    }
}