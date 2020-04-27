<?php

namespace App\Component\Db;

use App\Component\Util\DateTimeUtil;
use Doctrine\DBAL\DBALException;

abstract class WriteQueryAbstract extends QueryAbstract
{
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Executes an SQL INSERT/UPDATE/DELETE query with the given parameters
     * and returns the number of affected rows.
     *
     * @param string $query
     * @return int
     * @throws DBALException
     */
    protected function executeUpdate(string $query): int
    {
        $startTimeMs = DateTimeUtil::getTime();
        $numberRowsAffected = $this->getConnection()->executeUpdate($query);
        $endTimeMs = DateTimeUtil::getTime();
        $extraData = ['Time' => $endTimeMs-$startTimeMs, 'RowsAffected' => $numberRowsAffected];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $numberRowsAffected;
    }

    /**
     * @param string $query
     * @return int
     * @throws DBALException
     */
    protected function insertAndGetLastInsertedId(string $query): int
    {
        $this->executeUpdate($query);

        return $this->getConnection()->lastInsertId();
    }
}
