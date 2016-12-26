<?php

namespace Component\Elasticsearch;

use Component\Log\LogTrait;
use Component\Util\DateTimeUtil;
use Elasticsearch\Client;

class Query
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
     *
     * @return array
     */
    protected function fetchAll($query)
    {
        $startTimeMs = DateTimeUtil::getTime();
        $originalResult = $this->search($query);
        $endTimeMs = DateTimeUtil::getTime();

        $extraData = [
            static::TIME_QUERY => ($endTimeMs - $startTimeMs) / 1000,
            static::TOTAL_RESULTS => count($originalResult['suggest']['film-suggest'][0]['options'])
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return array_column($originalResult['suggest']['film-suggest'][0]['options'], '_source');
    }

    private function search($query)
    {
        $searchParams = '{
            "index": "' . $this->elasticsearchIndexName . '",
            "body": ' . $query . '
        }';

        $searchParams = json_decode($searchParams, true);

        return $this->getClient()->search($searchParams);
    }
}
