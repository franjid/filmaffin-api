<?php

namespace Component\Db;

class WriteDefaultQuery extends WriteQueryAbstract
{
    public function getDbPool(): string
    {
        return DbPoolAbstract::WRITE_DEFAULT;
    }
}
