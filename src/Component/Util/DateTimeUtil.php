<?php

namespace Component\Util;

class DateTimeUtil
{
    /**
     * Get time (ms).
     *
     * @return float
     */
    public static function getTime()
    {
        return microtime(true) * 1000;
    }
}
