<?php

declare(strict_types=1);

namespace Tests\Roboscript;

use App\Roboscript\Roboscript;
use PHPUnit\Framework\TestCase;

final class RoboscriptExecuteTest extends TestCase
{
  public function testDescriptionExamples() {
    $rs = new RoboscriptSingle();
    $this->assertSame("*", $rs->execute(""));
    $this->assertSame("******", $rs->execute("FFFFF"));
    $this->assertSame("******\r\n*    *\r\n*    *\r\n*    *\r\n*    *\r\n******", $rs->execute("FFFFFLFFFFFLFFFFFLFFFFFL"));
    $this->assertSame("    ****\r\n    *  *\r\n    *  *\r\n********\r\n    *   \r\n    *   ", $rs->execute("LFFFFFRFFFRFFFRFFFFFFF"));
    $this->assertSame("    ****\r\n    *  *\r\n    *  *\r\n********\r\n    *   \r\n    *   ", $rs->execute("LF5RF3RF3RF7"));
  }
}