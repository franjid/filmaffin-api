<?php

namespace App\Infrastructure\Component\Db;

use App\Infrastructure\Component\Log\LogTrait;
use Doctrine\DBAL\Connection;

abstract class QueryAbstract
{
    use LogTrait;

    public function __construct(private Connection $connection)
    {
    }

    abstract public function isReadOnly(): bool;

    abstract public function getDbPool(): string;

    protected function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    protected function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * Get DbDate in right format to save in database.
     *
     * @throws \Exception
     */
    protected function getDbDate(string $time = 'now'): string
    {
        $dateTime = new \DateTimeImmutable($time);

        return $this->quote($dateTime->format('Y-m-d H:i:s'));
    }

    final public function getInfoPoolConnection(): array
    {
        return [
            'Db' => $this->connection->getDatabase(),
            'Host' => $this->connection->getHost(),
            'Port' => $this->connection->getPort(),
            'ReadOnly' => $this->isReadOnly() ? 'yes' : 'no',
        ];
    }

    /**
     * Get custom data for logging.
     */
    public function getExtraDataLog(): array
    {
        return [
            'Class' => static::class,
            'Pool' => $this->getDbPool(),
            'Time' => -1,
        ];
    }

    /**
     * Quotes a given input parameter.
     *
     * @param mixed       $input the parameter to be quoted
     * @param string|null $type  the type of the parameter
     *
     * @return string the quoted parameter
     */
    public function quote(mixed $input, ?string $type = null): string
    {
        return $this->getConnection()->quote($input, $type);
    }

    public function quoteFromArray(array $inputs, ?string $type = null): array
    {
        $result = [];

        foreach ($inputs as $input) {
            $result[] = $this->quote($input, $type);
        }

        return $result;
    }
}
