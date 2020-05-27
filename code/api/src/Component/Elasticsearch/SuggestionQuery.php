<?php

namespace App\Component\Elasticsearch;

class SuggestionQuery extends QueryAbstract
{
    protected function fetchAll(string $query): array
    {
        $startTimeMs = microtime(true);
        $originalResult = $this->search($query);
        $endTimeMs = microtime(true);

        $extraData = [
            static::TIME_QUERY => ($endTimeMs - $startTimeMs),
            static::TOTAL_RESULTS => count($originalResult['suggest']['film-suggest'][0]['options'])
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return array_column($originalResult['suggest']['film-suggest'][0]['options'], '_source');
    }
}
