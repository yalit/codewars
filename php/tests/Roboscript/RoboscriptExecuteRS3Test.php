<?php

declare(strict_types=1);

namespace Tests\Roboscript;

use App\Roboscript\Roboscript;
use PHPUnit\Framework\TestCase;

final class RoboscriptExecuteRS3Test extends TestCase
{
  
  public function testDescriptionExamples() {
    $rs = new Roboscript();
    $message = 'Issue with input : "%s"';
    //legacy (RS2) tests
    $this->assertSame("*", $rs->execute(""), \sprintf($message, ""));
    $this->assertSame("******", $rs->execute("FFFFF"), \sprintf($message, "FFFFF"));
    $this->assertSame("******\r\n*    *\r\n*    *\r\n*    *\r\n*    *\r\n******", $rs->execute("FFFFFLFFFFFLFFFFFLFFFFFL"), \sprintf($message, "FFFFFLFFFFFLFFFFFLFFFFFL"));
    $this->assertSame("    ****\r\n    *  *\r\n    *  *\r\n********\r\n    *   \r\n    *   ", $rs->execute("LFFFFFRFFFRFFFRFFFFFFF"), \sprintf($message, "LFFFFFRFFFRFFFRFFFFFFF"));
    $this->assertSame("    ****\r\n    *  *\r\n    *  *\r\n********\r\n    *   \r\n    *   ", $rs->execute("LF5RF3RF3RF7"), \sprintf($message, "LF5RF3RF3RF7"));


    $this->assertSame("    ****\r\n    *  *\r\n    *  *\r\n********\r\n    *   \r\n    *   ", $rs->execute("LF5(RF3)(RF3R)1F7"), \sprintf($message, "LF5(RF3)(RF3R)1F7"));
    $this->assertSame("    ****\r\n    *  *\r\n    *  *\r\n********\r\n    *   \r\n    *   ", $rs->execute("(L(F5(RF3))(((R(F3R)F7))))"), \sprintf($message, "(L(F5(RF3))(((R(F3R)F7))))"));
    $this->assertSame("    *****   *****   *****\r\n    *   *   *   *   *   *\r\n    *   *   *   *   *   *\r\n    *   *   *   *   *   *\r\n*****   *****   *****   *", $rs->execute("F4L(F4RF4RF4LF4L)2F4RF4RF4"), \sprintf($message, "F4L(F4RF4RF4LF4L)2F4RF4RF4"));
    $this->assertSame("    *****   *****   *****\r\n    *   *   *   *   *   *\r\n    *   *   *   *   *   *\r\n    *   *   *   *   *   *\r\n*****   *****   *****   *", $rs->execute("F4L((F4R)2(F4L)2)2(F4R)2F4"), \sprintf($message, "F4L((F4R)2(F4L)2)2(F4R)2F4"));
  }
}