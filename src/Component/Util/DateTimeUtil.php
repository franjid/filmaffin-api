<?php

namespace App\Component\Util;

class DateTimeUtil
{
    public static function getTime(): float
    {
        return microtime(true) * 1000;
    }
}
