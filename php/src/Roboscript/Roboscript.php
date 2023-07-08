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
        $positions = [new Position(0, 0)];
        $dir = Direction::RIGHT;

        $i = 0;
        $execCode = str_split($code);
        $lastExec = "";
        while($i < count($execCode)) {
            $lastExec = $execCode[$i];
            $nb = 1;
            if ($i+1 < count($execCode) && \is_numeric($execCode[$i + 1])) {
                $temp = "";
                 while ($i + 1 < count($execCode) && \is_numeric($execCode[$i + 1])) {
                    $temp .= $execCode[$i + 1];
                    $i++;
                 }
                 $nb = (int)$temp;
            }

            $i++;
            if ($lastExec === "") {
                continue;
            }

            $dir = static::executeInstruction($lastExec, $nb, $positions, $dir);
        }

        return (new Grid($positions))->display();
    }

    private static function executeInstruction(string $inst, int $nb, array &$positions, Direction $dir): Direction 
    {
        for ($a = 0; $a < $nb; $a++) {
            $dir = match($inst) {
                "F" => static::executeForward($positions, $dir),
                "L" => $dir->goLeft(),
                "R" => $dir->goRight()
            };
        }

        return $dir;
    }

    private static function executeForward(array &$positions, Direction $dir): Direction 
    {
        $positions[] = $dir->nextPosition($positions[count($positions) - 1]);
        return $dir;
    }
    
}