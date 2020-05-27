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
     * àòùìéëu -> aouieeu
     * ÁOÒòoÍìI -> AOOooIiI
     * tiësto -> tiesto
     *
     * @param string $string
     *
     * @return string
     */
    public function removeDiacritics(string $string): string
    {
        $transliterator = Transliterator::createFromRules(
            ':: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;',
            Transliterator::FORWARD
        );

        return $transliterator->transliterate($string);
    }

    public function getSanitizedWordPermutations($inStr): array
    {
        $inStr = $this->removeDiacritics($inStr);
        $inStr = mb_ereg_replace(
            '#[[:punct:]]#', '', trim(str_replace(['(c)', '(s)'], ['', ''], mb_strtolower($inStr)))
        );

        $outArr = [];
        $tokenArr = explode(' ', $inStr);
        $numTokenArr = count($tokenArr);
        $pointer = 0;

        foreach ($tokenArr as $index => $value) {
            if (!empty($tokenArr[$index])) {
                $outArr[$pointer] = $value;
            }

            $tokenString = $value;
            $pointer++;

            for ($j = $index + 1; $j < $numTokenArr; $j++) {
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
