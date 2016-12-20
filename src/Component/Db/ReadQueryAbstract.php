<?php

namespace Component\Db;

use Component\Util\Util;
use Doctrine\DBAL\DBALException;

abstract class ReadQueryAbstract extends QueryAbstract
{
    const TIME_VAR_NAME = 'Time';
    const ROWS_AFFECTED_VAR_NAME = 'RowsAffected';

    /**
     * Check if it's read query
     *
     * @return bool
     */
    public function isReadOnly()
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
    protected function fetchAssoc($query)
    {
        $startTimeMs = Util::getTime();

        $result = $this->getConnection()->fetchAssoc($query);

        $endTimeMs = Util::getTime();

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result)
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * Return a object
     *
     * @param string $query
     * @param string $class Full name class (with namespace)
     * @return mixed
     * @throws DBALException
     */
    protected function fetchObject($query, $class = '\stdClass')
    {
        $startTimeMs = Util::getTime();

        $result = $this->getConnection()->executeQuery($query, [], [])->fetchObject($class);

        $endTimeMs = Util::getTime();

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
    protected function fetchAll($query)
    {
        $startTimeMs = Util::getTime();

        $result = $this->getConnection()->fetchAll($query);

        $endTimeMs = Util::getTime();

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
     * @param string $query
     * @param string|null $class Full name class (with namespace).
     *
     * @return array
     */
    protected function fetchAllObject($query, $class = '\stdClass')
    {
        $startTimeMs = Util::getTime();

        $result = $this->getConnection()->executeQuery($query)->fetchAll(\PDO::FETCH_CLASS, $class);

        $endTimeMs = Util::getTime();

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result)
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }

    /**
     * Returns column
     *
     * @param string $query
     *
     * @return mixed
     */
    protected function fetchColumn($query)
    {
        $startTimeMs = Util::getTime();

        $result = $this->getConnection()->fetchColumn($query);

        $endTimeMs = Util::getTime();

        $extraData = [
            static::TIME_VAR_NAME => $endTimeMs - $startTimeMs,
            static::ROWS_AFFECTED_VAR_NAME => count($result)
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return $result;
    }
}
