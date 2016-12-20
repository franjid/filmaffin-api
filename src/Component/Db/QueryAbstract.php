<?php

namespace Component\Db;

use Doctrine\DBAL\Connection;
use Component\Log\LogTrait;

abstract class QueryAbstract
{
    use LogTrait;

    /** @var Connection $connection */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * To know if query is read/write
     *
     * @return boolean
     */
    abstract public function isReadOnly();

    /**
     * Get database pool connection
     *
     * @return string
     */
    abstract public function getDbPool();

    /**
     * Set connection
     *
     * @param Connection $connection
     */
    protected function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get connection object
     *
     * @return Connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get DbDate in right format to save in database
     *
     * @param string $time
     * @return string
     */
    protected function getDbDate($time = 'now')
    {
        $dateTime = new \DateTime($time);

        return $this->quote($dateTime->format('Y-m-d H:i:s'));
    }

    /**
     * Get info connection
     *
     * @return array
     */
    final public function getInfoPoolConnection()
    {
        return [
            'Db' => $this->connection->getDatabase(),
            'Host' => $this->connection->getHost(),
            'Port' => $this->connection->getPort(),
            'ReadOnly' => $this->isReadOnly() ? 'yes' : 'no'
        ];
    }

    /**
     * Get custom data to do logging
     *
     * @return array
     */
    public function getExtraDataLog()
    {
        return [
            'Class' => static::class,
            'Pool'  => $this->getDbPool(),
            'Time'  => -1
        ];
    }

    /**
     * Quotes a given input parameter.
     *
     * @param mixed $input The parameter to be quoted.
     * @param string|null $type The type of the parameter.
     * @return string The quoted parameter.
     */
    public function quote($input, $type = null)
    {
        return $this->getConnection()->quote($input, $type);
    }

    /**
     * @param [] $inputs
     * @param string|null $type
     * @return array
     */
    public function quoteFromArray(array $inputs, $type = null)
    {
        $result = [];

        foreach ($inputs as $input)
        {
            $result[] = $this->quote($input, $type);
        }

        return $result;
    }
}
