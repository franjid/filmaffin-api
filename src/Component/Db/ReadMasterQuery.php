<?php

namespace App\Component\Db;

class ReadMasterQuery extends ReadQueryAbstract
{
    public function getDbPool(): string
    {
        return DbPoolAbstract::READ_MASTER;
    }
}
