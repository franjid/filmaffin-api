<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmsCount extends GlobalReadQuery
{
    public function getResult(?int $dateUpdatedNewestThanTimestamp): int
    {
        $query = 'SELECT';
        $query .= '   COUNT(*) AS numResults';
        $query .= ' FROM';
        $query .= ' film';

        if ($dateUpdatedNewestThanTimestamp !== null) {
            $query .= ' WHERE dateUpdated >= '.$dateUpdatedNewestThanTimestamp;
        }

        return (int) $this->fetchAssoc($query)['numResults'];
    }
}
