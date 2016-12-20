<?php

namespace Component\Db;

class GlobalReadQuery extends ReadQueryAbstract
{
    /**
     * @return string
     */
    public function getDbPool()
    {
        return DbPoolAbstract::GLOBAL_READ;
    }
}
