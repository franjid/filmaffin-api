<?php

namespace Component\Db;

class GlobalWriteQuery extends WriteQueryAbstract
{
    /**
     * @return string
     */
    public function getDbPool()
    {
        return DbPoolAbstract::GLOBAL_WRITE;
    }
}
