<?php

namespace App\Infrastructure\Component\Db;

class GlobalReadQuery extends ReadQueryAbstract
{
    public function getDbPool(): string
    {
        return DbPoolAbstract::GLOBAL_READ;
    }
}
