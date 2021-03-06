<?php

namespace App\Infrastructure\Component\Db;

class GlobalWriteQuery extends WriteQueryAbstract
{
    public function getDbPool(): string
    {
        return DbPoolAbstract::GLOBAL_WRITE;
    }
}
