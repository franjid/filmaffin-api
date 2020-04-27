<?php

namespace App\Component\Elasticsearch;

use App\Component\Util\DateTimeUtil;

class SuggestionQuery extends QueryAbstract
{
    protected function fetchAll(string $query): array
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
}
