<?php

declare(strict_types=1);

namespace App\Kyu4;

use ReflectionFunction;

class Currying 
{
    public static function curryPartial($fn, ...$args) {
        $func = new ReflectionFunction($fn);
        $fnParam = $func->getParameters();
        
        if (count($args) >= count($fnParam)) {
            $nbParam = count($fnParam) > 0 && $fnParam[0]->name === "curryArgs" ? count($args) : count($fnParam);
            return $fn(...array_slice($args,0, $nbParam));
        } 
        else {
          return function(...$curryArgs) use ($fn, $args) {
            return self::curryPartial($fn, ...$args, ...$curryArgs);
          };
        }
      }
}