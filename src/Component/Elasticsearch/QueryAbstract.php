<?php

namespace Component\Elasticsearch;

use Component\Log\LogTrait;
use Elasticsearch\Client;

abstract class QueryAbstract
{
    const TIME_QUERY = 'Time';
    const TOTAL_RESULTS = 'RowsAffected';

    use LogTrait;

    /** @var Client $client */
    private $client;

    /** @var string $elasticsearchIndexName */
    private $elasticsearchIndexName;

    /**
     * @param Client $client
     * @param string $elasticsearchIndexName
     */
    public function __construct(
        Client $client,
        $elasticsearchIndexName
    )
    {
        $this->client = $client;
        $this->elasticsearchIndexName = $elasticsearchIndexName;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
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
            'Pool' => $this->elasticsearchIndexName,
            'Time'  => -1
        ];
    }

    /**
     * @param string $query
     */
    abstract protected function fetchAll($query);

    protected function search($query)
    {
        $searchParams = '{
            "index": "' . $this->elasticsearchIndexName . '",
            "body": ' . $query . '
        }';

        $searchParams = json_decode($searchParams, true);

        return $this->getClient()->search($searchParams);
    }
}
