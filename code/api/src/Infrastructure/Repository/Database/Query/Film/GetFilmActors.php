<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmActors extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .= ' actor.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' JOIN assocFilmActor USING(idFilm)';
        $query .= ' JOIN actor USING(idActor)';
        $query .= ' WHERE idFilm = '.$idFilm;
        $query .= ' ORDER BY assocFilmActor.relevancePosition';

        return $this->fetchAll($query);
    }
}
