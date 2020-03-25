<?php

namespace Component\Util;

use Transliterator;

class StringUtil
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
}
