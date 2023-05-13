<?php

namespace App\Infrastructure\Component\Elasticsearch;

class SuggestionQuery extends QueryAbstract
{
    protected function fetchAll(string $query): array
    {
        $startTimeMs = microtime(true);
        $originalResult = $this->search($query);
        $endTimeMs = microtime(true);

        $extraData = [
            static::TIME_QUERY => ($endTimeMs - $startTimeMs),
            static::TOTAL_RESULTS => is_countable($originalResult['suggest']['film-suggest'][0]['options']) ? count($originalResult['suggest']['film-suggest'][0]['options']) : 0,
        ];
        $this->writeLog($query, array_merge($this->getExtraDataLog(), $extraData));

        return array_column($originalResult['suggest']['film-suggest'][0]['options'], '_source');
    }
}
