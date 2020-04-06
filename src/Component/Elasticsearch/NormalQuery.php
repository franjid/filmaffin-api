<?php

namespace App\Component\Elasticsearch;

use App\Component\Util\DateTimeUtil;

class NormalQuery extends QueryAbstract
{
    protected function fetchAll(string $query): array
    {
        $startTimeMs = DateTimeUtil::getTime();
        $originalResult = $this->search($query);
        $endTimeMs = DateTimeUtil::getTime();

        $extraData = [
            static::TIME_QUERY => ($endTimeMs - $startTimeMs) / 1000,
            static::TOTAL_RESULTS => (int) $originalResult['hits']['total']
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        $result = array_column($originalResult['hits']['hits'], '_source');

        return $result ?: [];
    }
}
