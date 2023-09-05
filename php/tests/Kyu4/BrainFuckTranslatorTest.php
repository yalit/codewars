<?php

declare(strict_types=1);

namespace Tests\Kyu4;

use App\Kyu4\BrainFuckTranslator;
use PHPUnit\Framework\TestCase;

/**
 * @property BrainFuckTranslator $bft
 */
class BrainFuckTranslatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->bft = new BrainFuckTranslator();
    }

    public function testBasic() {
        $this->assertEquals("*p += 4;\n", $this->bft->brainfuck_to_c("++++"));
        $this->assertEquals("*p -= 4;\n", $this->bft->brainfuck_to_c("----"));

        $this->assertEquals("p += 4;\n", $this->bft->brainfuck_to_c(">>>>"));
        $this->assertEquals("p -= 4;\n", $this->bft->brainfuck_to_c("<<<<"));

        $this->assertEquals("putchar(*p);\n", $this->bft->brainfuck_to_c("."));
        $this->assertEquals("*p = getchar();\n", $this->bft->brainfuck_to_c(","));

        $this->assertEquals("Error!", $this->bft->brainfuck_to_c("[[[]]"));

        $this->assertEquals("", $this->bft->brainfuck_to_c("[][]"));

        $this->assertEquals("if (*p) do {\n  putchar(*p);\n} while (*p);\n", $this->bft->brainfuck_to_c("[.]"));
    }
}
