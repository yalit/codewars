<?php

namespace App\Kyu5;

class PhoneDirectory
{
    public function phone($allEntries, $num): string {
        $infos = explode("\n", $allEntries);
        $phoneBook = [];
        foreach($infos as $info) {
            $entry = new PhoneDirectoryEntry(str_split($info));
            if (array_key_exists($entry->phone, $phoneBook)) {
                $phoneBook[$entry->phone][] = $entry;
            } else {
                $phoneBook[$entry->phone] = [$entry];
            }
        }

        if (!array_key_exists($num, $phoneBook)) {
            return sprintf("Error => Not found: %s", $num);
        } else if (count($phoneBook[$num]) > 1) {
            return sprintf("Error => Too many people: %s", $num);
        }

        return (string)$phoneBook[$num][0];
    }
}