<?php

declare(strict_types=1);

namespace App\Roboscript;

/**
 * Implementing solutions for the series of Kata on Codewards Roboscript starting here : https://www.codewars.com/kata/roboscript-number-1-implement-syntax-highlighting
 */
final class Roboscript
{
    /**
     * 
     * @var string[][] $positions
     */
    private array $positions = [];

    private Direction $currentDirection;

    public function highlight(string $code): string
    {
        return \preg_replace(
            ['/(F+)/', '/(L+)/', '/(R+)/', '/([0-9]+)/'],
            ['<span style="color: pink">$1</span>', '<span style="color: red">$1</span>', '<span style="color: green">$1</span>', '<span style="color: orange">$1</span>',],
            $code
        );
    }

    public function execute(string $code): string
    {
        //initialize
        /** @var Position[] $positions */
        $this->positions = [[0, 0]];
        $this->currentDirection = new Direction(Direction::RIGHT);
        
        $this->doExecute(str_split($code));

        return (new Grid($this->positions))->display();
    }

    /**
     * 
     * @param string[] $code
     * @param Position[] $positions
     * @return Position[]
     */
    private function doExecute(array $code): void
    {
        if (count($code) === 0) {
            return;
        }

        //parse
        $nextInstruction = InstructionParser::getNextInstruction($code);
        $this->runInstruction($nextInstruction);
        //next
        $this->doExecute(\array_slice($code, $nextInstruction->size()));
    }

    private function runInstruction(Instruction $instruction): void
    {
        for($a = 0; $a < $instruction->getNb(); $a++) {
            if (\in_array($instruction->getText(), Instruction::allInstructions())) {
                $this->action($instruction->getText());
            } else {
                self::doExecute(\str_split($instruction->getText()));
            }
        }
        
    }

    private function action(string $action): void
    {
        $position = end($this->positions);
        match($action) {
            Instruction::FORWARD => $this->positions[] = $this->currentDirection->nextPosition($position),
            Instruction::LEFT => $this->currentDirection = $this->currentDirection->goLeft(),
            Instruction::RIGHT => $this->currentDirection = $this->currentDirection->goRight()
        };
    }
}