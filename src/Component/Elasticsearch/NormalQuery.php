<?php

namespace Component\Elasticsearch;

use Component\Util\DateTimeUtil;

class NormalQuery extends QueryAbstract
{
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
            static::TOTAL_RESULTS => count($originalResult['hits']['total'])
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        $result = array_column($originalResult['hits']['hits'], '_source');

        return $result ? $result : [];
    }
}
