<?php

namespace App\Kyu5;

class PhoneDirectoryEntry
{
    public string $phone = "";
    public string $name = "";
    public string $address = "";

    /**
     *  @param string[] $entry
     */
    public function __construct(array $entry) {
        $i = 0;
        while($i < count($entry)) {
            $l = $entry[$i];
            if ($l === "+") {
                $i += $this->parsePhone($entry, $i);
            }

            else if ($l === "<") {
                $i += $this->parseName($entry, $i);
            }

            else if(ctype_alnum($l)){
                $i += $this->parseAddress($entry, $i);
            }

            else {
                $i +=1;
            }
        }
    }

    public function __toString(): string {
        return sprintf("Phone => %s, Name => %s, Address => %s", $this->phone, $this->name, $this->address);
    }

    /**
     *  @param string[] $entry
     */
    private function parsePhone(array $entry, int $pos): int {
        $steps = 15;
        if ($entry[$pos + 2] === "-") {
            $steps = 14;
        }
        $this->phone = trim(implode("", array_slice($entry, $pos +1 , $steps)));
        return $steps + 1;
    }

    /**
     *  @param string[] $entry
     */
    private function parseName(array $entry, int $pos): int {
        $name = "";
        $k = $pos + 1;
        while ($entry[$k] !== ">") {
            $name .= $entry[$k];
            $k += 1;
        }

        $this->name = $name;
        return $k-$pos;
    }

    /**
     *  @param string[] $entry
     */
    private function parseAddress(array $entry, int $pos): int {
        if($this->address !== "") {
            $this->address .= " ";
        }

        $clutter = ["/", ";", "?", "_", "$", ","];

        $k = $pos;
        while (count($entry) > $k && $entry[$k] !== "+" && $entry[$k] !== "<") {
            if(!in_array($entry[$k], $clutter)) {
                $this->address .= $entry[$k];
            } else {
                $this->address .= " ";
            }
            $k += 1;
        }

        $this->address = str_replace("  ", " ", trim($this->address));
        return $k-$pos;
    }
}