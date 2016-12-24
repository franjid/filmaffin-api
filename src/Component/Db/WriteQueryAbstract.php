<?php

namespace Component\Db;

use Component\Util\DateTimeUtil;
use Doctrine\DBAL\DBALException;

abstract class WriteQueryAbstract extends QueryAbstract
{
    /**
     * Check if is read query
     *
     * @return bool
     */
    public function isReadOnly()
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
    protected function executeUpdate($query)
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
    protected function insertAndGetLastInsertedId($query)
    {
        $this->executeUpdate($query);

        return $this->getConnection()->lastInsertId();
    }
}
