<?php

declare(strict_types=1);

namespace App\Roboscript;

final class InstructionParser
{

    public static function parseCode(string $code): string
    {
        $i = 0;
        $execCode = str_split($code);
        $instructions = "";
        while($i < count($execCode)) {
            $instruction = InstructionParser::getNextInstruction(\array_slice($execCode, $i));
            $instructions .= (string)$instruction;
            $i += $instruction->size();
        }

        return $instructions;
    }

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
            $nbInfos = static::getNb($code, 1);
            return new Instruction($c, $nbInfos[0], false, $nbInfos[1]);
        }

        $bracketSize = static::getBracketSize($code);
        $nbInfos = static::getNb($code, $bracketSize);
        return new Instruction(join('', \array_slice($code, 1, $bracketSize - 2)), $nbInfos[0], true, $nbInfos[1]);
    }

    /**
     * 
     * @param string[] $code 
     * @return {string, bool} //bool equals if no numeric data in the code
     */
    private static function getNb(array $code, int $position): array
    {
        if (count($code) <= $position || !\is_numeric($code[$position])) {
            return [1, false];
        }

        $temp = "";
        while ($position < count($code) && \is_numeric($code[$position])) {
            $temp .= $code[$position];
            $position++;
        }

        return [(int)$temp, true];
    }

    private static function getBracketSize(array $code): int
    {
        $openedBrackets = 1;
        $i = 1;

        while($i < count($code) && $openedBrackets > 0) {
            if ($code[$i] === '(') {
                $openedBrackets +=1;
            }
            if ($code[$i] === ')') {
                $openedBrackets -=1;
            }
            $i++;
        }
        return $i;
    }

}
