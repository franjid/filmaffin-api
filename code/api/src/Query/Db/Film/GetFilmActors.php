<?php

namespace App\Query\Db\Film;

use App\Component\Db\GlobalReadQuery;

class GetFilmActors extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .=  ' actor.name';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LEFT JOIN assocFilmActor USING(idFilm)';
        $query .= ' LEFT JOIN actor USING(idActor)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmActor.relevancePosition';

        return $this->fetchAll($query);
    }
}
