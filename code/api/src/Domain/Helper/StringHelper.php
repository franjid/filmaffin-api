<?php

namespace App\Domain\Helper;

use Transliterator;

class StringHelper
{
    /**
     * Replace letters with diacritics into a "normal" mode
     *
     * Examples:
     *
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

    /**
     * Returns word permutations of a string
     *
     * Example:
     *
     * La vita è bella ->
     *     'la',
     *     'la vita',
     *     'la vita e',
     *     'la vita e bella',
     *     'vita',
     *     'vita e',
     *     'vita e bella',
     *     'e',
     *     'e bella',
     *     'bella',
     *
     * @param $string
     *
     * @return array
     */
    public function getSanitizedWordPermutations(string $string): array
    {
        $string = $this->removeDiacritics($string);
        $string = mb_ereg_replace(
            '#[[:punct:]]#', '', trim(str_replace(['(c)', '(s)'], ['', ''], mb_strtolower($string)))
        );

        $outArr = [];
        $tokenArr = explode(' ', $string);
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
