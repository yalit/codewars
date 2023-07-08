<?php

declare(strict_types=1);

namespace Tests\Roboscript;

use App\Roboscript\Roboscript;
use PHPUnit\Framework\TestCase;

final class RoboscriptHighlightTest extends TestCase
{
    public function testDescriptionExamples() {
        $this->assertSame("<span style=\"color: pink\">F</span><span style=\"color: orange\">3</span><span style=\"color: green\">R</span><span style=\"color: pink\">F</span><span style=\"color: orange\">5</span><span style=\"color: red\">L</span><span style=\"color: pink\">F</span><span style=\"color: orange\">7</span>", Roboscript::highlight("F3RF5LF7"));
        $this->assertSame("<span style=\"color: pink\">FFF</span><span style=\"color: green\">R</span><span style=\"color: orange\">345</span><span style=\"color: pink\">F</span><span style=\"color: orange\">2</span><span style=\"color: red\">LL</span>", Roboscript::highlight("FFFR345F2LL"));
      }
}