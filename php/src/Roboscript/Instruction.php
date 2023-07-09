<?php

declare(strict_types=1);

namespace App\Roboscript;

class Instruction
{
    public const FORWARD = "F";
    public const LEFT = "L";
    public const RIGHT = "R";

    public function __construct(private string $instruction, private int $nb = 1, private bool $withBrackets = false, private bool $withPrintedNumber = false)
    {
    }

    public function getText(): string
    {
        return $this->instruction;
    }
    
    public function getNb(): int
    {
        return $this->nb;
    }

    public function __toString(): string
    {
        $parsedInstruction = "";

        if (\in_array($this->getText(), self::allInstructions())){
            $parsedInstruction = $this->getText();
        } else {
            $parsedInstruction = InstructionParser::parseCode($this->instruction);
        }
        
        $ret = $parsedInstruction;
        for($a = 1; $a < $this->nb; $a++) {
            $ret .= $parsedInstruction;
        }

        return $ret;
    }

    /**
     * 
     * @return string[] 
     */
    public static function allInstructions(): array
    {
        return [self::FORWARD, self::LEFT, self::RIGHT];
    }

    public function size(): int 
    {
        return \strlen($this->instruction) + ($this->nb === 1 ? ($this->withPrintedNumber ? \strlen((string)$this->nb) : 0) : \strlen((string)$this->nb)) + ($this->withBrackets ? 2 : 0);
    }
}