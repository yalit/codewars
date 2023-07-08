<?php

declare(strict_types=1);

namespace Tests\Kyu4;

use App\Kyu4\Currying;
use PHPUnit\Framework\TestCase;

class CurryingTest extends TestCase
{
    public function testFunctionWithThreeRandomParameters() {
        $add3 = function ($a, $b, $c) {
          return $a + $b + $c;
        };
        $a = 1;
        $b = 2;
        $c = 3;
        $sum = $a + $b + $c;
        
        $this->assertSame($add3($a, $b, $c), $sum);
        $this->assertSame(Currying::curryPartial($add3)($a)($b)($c), $sum);
        $this->assertSame(Currying::curryPartial($add3, $a)($b)($c), $sum);
        $this->assertSame(Currying::curryPartial($add3, $a)($b, $c), $sum);
        $this->assertSame(Currying::curryPartial($add3, $a, $b, $c), $sum);
        $this->assertSame(Currying::curryPartial($add3, $a, $b, $c, 20), $sum);
        $this->assertSame(Currying::curryPartial($add3)($a, $b, $c), $sum);
        $this->assertSame(Currying::curryPartial($add3)()($a, $b, $c), $sum);
        $this->assertSame(Currying::curryPartial($add3)()($a)()()($b, $c), $sum);
        $this->assertSame(Currying::curryPartial($add3)()($a)()()($b, $c, 5, 6, 7), $sum);
    
        $this->assertSame(Currying::curryPartial(Currying::curryPartial(Currying::curryPartial($add3, $a), $b), $c), $sum);
        $this->assertSame(Currying::curryPartial(Currying::curryPartial($add3, $a, $b), $c), $sum);
        $this->assertSame(Currying::curryPartial(Currying::curryPartial($add3, $a), $b, $c), $sum);
    
        $this->assertSame(Currying::curryPartial(Currying::curryPartial($add3, $a), $b)($c), $sum);
        $this->assertSame(Currying::curryPartial(Currying::curryPartial($add3, $a)($b), $c), $sum);
        
        $this->assertSame(Currying::curryPartial(Currying::curryPartial(Currying::curryPartial($add3, $a)), $b, $c), $sum);
      }

      public function testFunctionWithTwoRandomParameters() {
    
        $add2 = function ($a, $b) {
          return $a + $b;
        };
        
        $a = 1;
        $b = 2;
        $sum = $a + $b;
        
        $this->assertSame($add2($a, $b), $sum);
        $this->assertSame(Currying::curryPartial($add2)($a)($b), $sum);
        $this->assertSame(Currying::curryPartial($add2, $a, $b), $sum);
        $this->assertSame(Currying::curryPartial($add2, $a, $b, 20), $sum);
        $this->assertSame(Currying::curryPartial($add2)($a, $b), $sum);
        $this->assertSame(Currying::curryPartial($add2)()($a, $b), $sum);
        $this->assertSame(Currying::curryPartial($add2)()($a)()()($b), $sum);
        $this->assertSame(Currying::curryPartial($add2)()($a)()()($b, 5, 6, 7), $sum);
        
        $this->assertSame(Currying::curryPartial(Currying::curryPartial($add2, $a), $b), $sum);
      }
      
      public function testFunctionWithOneRandomParameter() {
        $double = function ($a) {
          return $a * 2;
        };    
        
        $a = 5;
        $result = $a * 2;
      
        $this->assertSame($double($a), $result);
        $this->assertSame(Currying::curryPartial($double)($a), $result);
        $this->assertSame(Currying::curryPartial($double, $a), $result);
        $this->assertSame(Currying::curryPartial($double)()($a), $result);
        
      }
      
      public function testFunctionWithNoParameters() {
        $a = 5;
        
        $double = function () use ($a) {
          return $a * 2;
        };
        
        $result = $a * 2;
      
        $this->assertSame($double(), $result);
        $this->assertSame(Currying::curryPartial($double), $result);
        
      }
      
      
      public function testFunctionWithFourRandomParameters() {
        
        $add4 = function ($a, $b, $c, $d) {
          return 4*$a + 3*$b + 2*$c + $d;
        };
        
        $a = 4;
        $b = 3;
        $c = 2;
        $d = 1;
        $sum = 4*$a + 3*$b + 2*$c + $d;
        
        $this->assertSame($add4($a, $b, $c, $d), $sum);
        $this->assertSame(Currying::curryPartial($add4)($a)($b)($c)($d), $sum);
        $this->assertSame(Currying::curryPartial($add4)($a, $b)($c)($d), $sum);
        $this->assertSame(Currying::curryPartial($add4, $a, $b)($c)($d), $sum);
        $this->assertSame(Currying::curryPartial($add4, $a, $b)($c, $d), $sum);
        $this->assertSame(Currying::curryPartial(Currying::curryPartial($add4, $a, $b), $c, $d), $sum);
        $this->assertSame(Currying::curryPartial(Currying::curryPartial($add4, $a, $b)($c), $d), $sum);
        $this->assertSame(Currying::curryPartial(Currying::curryPartial($add4, $a)($b, $c), $d), $sum);
        $this->assertSame(Currying::curryPartial(Currying::curryPartial(Currying::curryPartial($add4, $a), $b), $c, $d), $sum);
        $this->assertSame(Currying::curryPartial(Currying::curryPartial(Currying::curryPartial($add4, $a), $b), $c)($d), $sum);
        $this->assertSame(Currying::curryPartial(Currying::curryPartial(Currying::curryPartial(Currying::curryPartial($add4, $a), $b), $c), $d), $sum);
      }
    
      public function testStateIsntPreserved() {
        
        $add = function($a, $b, $c) {
          return $a + $b + $c;
        };
        
        $add1 = Currying::curryPartial($add, 1);
        $this->assertSame($add1(2, 3), 6);
        $this->assertSame($add1(4, 5), 10);
    
        $add2 = Currying::curryPartial($add)(2);
        $this->assertSame($add2(3, 4), 9);
        $this->assertSame($add2(5)(6), 13);
    
        $it0 = [Currying::curryPartial($add)];
        $it1 = [$it0[0](0),
                $it0[0](1)];
        $it2 = [$it1[0](0), $it1[1](0),
                $it1[0](2), $it1[1](2)];
        $it3 = [$it2[0](0), $it2[1](0), $it2[2](0), $it2[3](0),
                $it2[0](4), $it2[1](4), $it2[2](4), $it2[3](4)];
    
        $this->assertSame($it3, [0, 1, 2, 3, 4, 5, 6, 7], 'tree of calls');
      }
}
