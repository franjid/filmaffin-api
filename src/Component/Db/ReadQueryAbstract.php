<?php

namespace Component\Db;

use Component\Util\DateTimeUtil;
use Doctrine\DBAL\DBALException;

abstract class ReadQueryAbstract extends QueryAbstract
{
    public const TIME_VAR_NAME = 'Time';
    public const ROWS_AFFECTED_VAR_NAME = 'RowsAffected';

    public function isReadOnly(): bool
    {
        return true;
    }

    /**
     * Returns the first row of the result as an associative array
     *
     * @param string $query
     *
     * @return array
     */
    protected function fetchAssoc(string $query): array
    {
        $startTimeMs = DateTimeUtil::getTime();

        $result = $this->getConnection()->fetchAssoc($query);

        $endTimeMs = DateTimeUtil::getTime();

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result)
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * @param string $query
     * @param string $class Full name class (with namespace)
     * @return mixed
     * @throws DBALException
     */
    protected function fetchObject(string $query, string $class = '\stdClass')
    {
        $startTimeMs = DateTimeUtil::getTime();

        $result = $this->getConnection()->executeQuery($query, [], [])->fetchObject($class);

        $endTimeMs = DateTimeUtil::getTime();

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => $result ? 1 : 0
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * Returns the result as an associative array.
     *
     * @param string $query
     *
     * @return array
     */
    protected function fetchAll(string $query): array
    {
        $startTimeMs = DateTimeUtil::getTime();

        $result = $this->getConnection()->fetchAll($query);

        $endTimeMs = DateTimeUtil::getTime();

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result)
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * Returns the result as an associative array of objects
     *
     * @param string      $query
     * @param string|null $class Full name class (with namespace).
     *
     * @return array
     * @throws DBALException
     */
    protected function fetchAllObject(string $query, ?string $class = '\stdClass'): array
    {
        $startTimeMs = DateTimeUtil::getTime();

        $result = $this->getConnection()->executeQuery($query)->fetchAll(\PDO::FETCH_CLASS, $class);

        $endTimeMs = DateTimeUtil::getTime();

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result)
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * @param string $query
     *
     * @return mixed
     */
    protected function fetchColumn(string $query)
    {
        $startTimeMs = DateTimeUtil::getTime();

        $result = $this->getConnection()->fetchColumn($query);

        $endTimeMs = DateTimeUtil::getTime();

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result)
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }
}
