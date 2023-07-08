<?php

declare(strict_types=1);

namespace App\Roboscript;

final class InstructionParser
{
    /**
     * 
     * @param string[] $code 
     */
    public static function getNextInstruction(array $code): Instruction
    {
        if (count($code) === 0) {
            return new Instruction("");
        }

        $c = $code[0];
        if (\in_array($c, Instruction::allInstructions())) {
            return new Instruction($c, static::getNb($code, 1));
        }
    }

    /**
     * 
     * @param string[] $code 
     */
    private static function getNb(array $code, int $position): int
    {
        if (!\is_numeric($code[$position])) {
            return 1;
        }
        $temp = "";
        while ($position < count($code) && \is_numeric($code[$position])) {
            $temp .= $code[$position];
            $position++;
        }

        return (int)$temp;
    }

}
