<?php

namespace App\Infrastructure\Component\Elasticsearch;

class NormalQuery extends QueryAbstract
{
    protected function fetchAll(string $query): array
    {
        $startTimeMs = microtime(true);
        $originalResult = $this->search($query);
        $endTimeMs = microtime(true);

        $extraData = [
            static::TIME_QUERY => ($endTimeMs - $startTimeMs),
            static::TOTAL_RESULTS => (int) $originalResult['hits']['total'],
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        $result = array_column($originalResult['hits']['hits'], '_source');

        return $result ?: [];
    }
}
