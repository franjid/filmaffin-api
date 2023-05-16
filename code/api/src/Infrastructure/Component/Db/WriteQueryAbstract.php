<?php

namespace App\Infrastructure\Component\Db;

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
     * @throws DBALException
     */
    protected function executeUpdate(string $query): int
    {
        $startTimeMs = microtime(true);
        $numberRowsAffected = $this->getConnection()->executeUpdate($query);
        $endTimeMs = microtime(true);
        $extraData = ['Time' => $endTimeMs - $startTimeMs, 'RowsAffected' => $numberRowsAffected];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $numberRowsAffected;
    }

    /**
     * @throws DBALException
     */
    protected function insertAndGetLastInsertedId(string $query): int
    {
        $this->executeUpdate($query);

        return $this->getConnection()->lastInsertId();
    }
}
