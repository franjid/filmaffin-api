<?php

namespace App\Infrastructure\Component\Elasticsearch;

use App\Infrastructure\Component\Log\LogTrait;
use Elasticsearch\Client;

abstract class QueryAbstract
{
    protected const TIME_QUERY = 'Time';
    protected const TOTAL_RESULTS = 'RowsAffected';

    use LogTrait;

    private Client $client;
    private string $elasticsearchIndexName;

    public function __construct(
        Client $client,
        string $elasticsearchIndexName
    )
    {
        $this->client = $client;
        $this->elasticsearchIndexName = $elasticsearchIndexName;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Get custom data for logging
     *
     * @return array
     */
    public function getExtraDataLog(): array
    {
        return [
            'Class' => static::class,
            'Pool' => $this->elasticsearchIndexName,
            'Time' => -1,
        ];
    }

    abstract protected function fetchAll(string $query): array;

    protected function search(string $query): array
    {
        $searchParams = '{
            "index": "' . $this->elasticsearchIndexName . '",
            "body": ' . $query . '
        }';

        $searchParams = json_decode($searchParams, true, 512, JSON_THROW_ON_ERROR);

        return $this->getClient()->search($searchParams);
    }
}
