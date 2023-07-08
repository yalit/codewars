<?php

declare(strict_types=1);

namespace Tests\Roboscript;

use App\Roboscript\Roboscript;
use PHPUnit\Framework\TestCase;

final class RoboscriptExecuteTest extends TestCase
{
  public function testDescriptionExamples() {
    $this->assertSame("*", Roboscript::execute(""));
    $this->assertSame("******", Roboscript::execute("FFFFF"));
    $this->assertSame("******\r\n*    *\r\n*    *\r\n*    *\r\n*    *\r\n******", Roboscript::execute("FFFFFLFFFFFLFFFFFLFFFFFL"));
    $this->assertSame("    ****\r\n    *  *\r\n    *  *\r\n********\r\n    *   \r\n    *   ", Roboscript::execute("LFFFFFRFFFRFFFRFFFFFFF"));
    $this->assertSame("    ****\r\n    *  *\r\n    *  *\r\n********\r\n    *   \r\n    *   ", Roboscript::execute("LF5RF3RF3RF7"));
  }
}