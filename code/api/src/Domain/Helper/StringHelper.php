<?php

namespace App\Domain\Helper;

use Transliterator;

class StringHelper
{
    /**
     * Replace letters with diacritics into a "normal" mode
     *
     * Examples:
     * abcd -> abcd
     * èe -> ee
     * € -> €
     * àòùìéëü -> aouieeu
     * àòùìéëü -> aouieeu
     * tiësto -> tiesto
     *
     * @param string $string
     *
     * @return string
     */
    public static function removeDiacritics(string $string): string
    {
        $transliterator = Transliterator::createFromRules(
            ':: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;',
            Transliterator::FORWARD
        );

        return $transliterator->transliterate($string);
    }

    public static function getSanitizedWordPermutations($inStr): array
    {
        $inStr = static::removeDiacritics($inStr);
        $inStr = mb_ereg_replace(
            '#[[:punct:]]#', '', trim(str_replace(['(c)', '(s)'], ['', ''], mb_strtolower($inStr)))
        );

        $outArr = [];
        $tokenArr = explode(' ', $inStr);
        $numTokenArr = count($tokenArr);
        $pointer = 0;

        for ($i = 0; $i < $numTokenArr; $i++) {
            if (!empty($tokenArr[$i])) {
                $outArr[$pointer] = $tokenArr[$i];
            }
            $tokenString = $tokenArr[$i];
            $pointer++;

            for ($j = $i + 1; $j < $numTokenArr; $j++) {
                $tokenString .= ' ' . $tokenArr[$j];
                if (!empty($tokenString)) {
                    $outArr[$pointer] = $tokenString;
                }

                $pointer++;
            }
        }

        return $outArr;
    }
}
