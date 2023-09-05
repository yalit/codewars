<?php

namespace App\Kyu4;

class BrainFuckTranslator
{
    function brainfuck_to_c($source_code){
        $optimized = str_split($this->optimize($source_code));

        if (!$this->isOk($optimized)) {
            return "Error!";
        }

        $algo = "";
        $i = 0;
        $level = 0;
        while ($i < count($optimized)) {
            if (in_array($optimized[$i], ['+', '-', '<', '>'])) {
                $i += $this->treatChar($optimized, $i, $algo, $level);
            } else if ($optimized[$i] === '.'){
                $algo .= str_pad("", $level * 2, " ", STR_PAD_LEFT) . "putchar(*p);\n";
                $i++;
            } else if ($optimized[$i] === ',') {
                $algo .= str_pad("", $level * 2, " ", STR_PAD_LEFT) . "*p = getchar();\n";
                $i++;
            } else if ($optimized[$i] === '[') {
                $algo .= str_pad("", $level * 2, " ", STR_PAD_LEFT) . "if (*p) do {\n";
                $i++;
                $level++;
            } else if ($optimized[$i] === ']') {
                $level--;
                $algo .= str_pad("", $level * 2, " ", STR_PAD_LEFT) . "} while (*p);\n";
                $i++;
            }
        }

        return $algo;
    }

    function optimize($s) {
        $rs = str_replace('+-', '', str_replace('<>', '', str_replace('[]', '', str_replace('><', '', $s))));

        if ($rs === $s) {
            return $s;
        }

        return $this->optimize($rs);
    }

    function isOk($s) {
        $braces = str_split(array_reduce($s, function($b, $c) { return $c === '[' || $c === ']' ? $b.$c : $b;}, ""));

        if ($braces === [""]) return true;

        $n = count($braces);
        if ($n % 2 !== 0) return false;

        $i = 0;
        $tb = 0;
        while ($i < $n) {
            if ($braces[$i] === '[') {
                $tb++;
            } else {
                if ($tb === 0) {
                    return false;
                }
                $tb--;
            }
            $i++;
        }

        return true;
    }

    function treatChar($s, $i, &$algo, $level) {
        $n = 0;
        while($i + $n < count($s) && $s[$i] === $s[$i + $n]) {
            $n++;
        }

        $algo .= str_pad("", $level * 2, " ", STR_PAD_LEFT) .
            sprintf(
                "%sp %s= %d;\n",
                ($s[$i] === "+" || $s[$i] === "-") ? "*" : "",
                ($s[$i] === "+" || $s[$i] === ">") ? "+" : "-",
                $n
            )
        ;
        return $n;
    }
}
