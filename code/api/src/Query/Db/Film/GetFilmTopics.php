<?php

namespace App\Query\Db\Film;

use App\Component\Db\GlobalReadQuery;

class GetFilmTopics extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .=  ' topic.name';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LEFT JOIN assocFilmTopic USING(idFilm)';
        $query .= ' LEFT JOIN topic USING(idTopic)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmTopic.relevancePosition';

        return $this->fetchAll($query);
    }
}