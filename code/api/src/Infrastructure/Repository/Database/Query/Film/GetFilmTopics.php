<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmTopics extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .= ' topic.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' JOIN assocFilmTopic USING(idFilm)';
        $query .= ' JOIN topic USING(idTopic)';
        $query .= ' WHERE idFilm = '.$idFilm;
        $query .= ' ORDER BY assocFilmTopic.relevancePosition';

        return $this->fetchAll($query);
    }
}
