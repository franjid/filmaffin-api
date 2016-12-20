<?php


namespace Component\Db;

class ReadSlaveQuery extends ReadQueryAbstract
{
    /**
     * @return string
     */
    public function getDbPool()
    {
        return DbPoolAbstract::READ_SLAVE;
    }
}
