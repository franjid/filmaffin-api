<?php


namespace Component\Db;

class WriteDefaultQuery extends WriteQueryAbstract
{
    /**
     * @return string
     */
    public function getDbPool()
    {
        return DbPoolAbstract::WRITE_DEFAULT;
    }
}
