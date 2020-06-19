<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmsCount extends GlobalReadQuery
{
    public function getResult(): int
    {
        $query = 'SELECT';
        $query .= '   COUNT(*) AS numResults';
        $query .= ' FROM';
        $query .= ' film';

        return (int) $this->fetchAssoc($query)['numResults'];
    }
}
