<?php

namespace App\Infrastructure\Component\Db;

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
     * Returns the first row of the result as an associative array.
     *
     * @return array|false|mixed[]
     *
     * @throws DBALException
     */
    protected function fetchAssoc(string $query)
    {
        $startTimeMs = microtime(true);

        $result = $this->getConnection()->fetchAssoc($query);

        $endTimeMs = microtime(true);

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => is_array($result) ? count($result) : 0,
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * @param string $class Full name class (with namespace)
     *
     * @return mixed
     *
     * @throws DBALException
     */
    protected function fetchObject(string $query, string $class = '\stdClass')
    {
        $startTimeMs = microtime(true);

        $result = $this->getConnection()->executeQuery($query, [], [])->fetchObject($class);

        $endTimeMs = microtime(true);

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => $result ? 1 : 0,
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * Returns the result as an associative array.
     */
    protected function fetchAll(string $query): array
    {
        $startTimeMs = microtime(true);

        $result = $this->getConnection()->fetchAll($query);

        $endTimeMs = microtime(true);

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result),
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * Returns the result as an associative array of objects.
     *
     * @param string|null $class full name class (with namespace)
     *
     * @throws DBALException
     */
    protected function fetchAllObject(string $query, ?string $class = '\stdClass'): array
    {
        $startTimeMs = microtime(true);

        $result = $this->getConnection()->executeQuery($query)->fetchAll(\PDO::FETCH_CLASS, $class);

        $endTimeMs = microtime(true);

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result),
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * @return mixed
     */
    protected function fetchColumn(string $query)
    {
        $startTimeMs = microtime(true);

        $result = $this->getConnection()->fetchColumn($query);

        $endTimeMs = microtime(true);

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result),
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }
}
