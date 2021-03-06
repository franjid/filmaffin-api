<?php

namespace App\Infrastructure\Component\Db;

class ReadSlaveQuery extends ReadQueryAbstract
{
    public function getDbPool(): string
    {
        return DbPoolAbstract::READ_SLAVE;
    }
}
