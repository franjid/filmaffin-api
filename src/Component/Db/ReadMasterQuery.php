<?php


namespace Component\Db;

class ReadMasterQuery extends ReadQueryAbstract
{

    /**
     * @return string
     */
    public function getDbPool()
    {
        return DbPoolAbstract::READ_MASTER;
    }
}
