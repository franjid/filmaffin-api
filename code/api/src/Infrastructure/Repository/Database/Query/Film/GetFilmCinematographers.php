<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmCinematographers extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .= ' cinematographer.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' JOIN assocFilmCinematographer USING(idFilm)';
        $query .= ' JOIN cinematographer USING(idCinematographer)';
        $query .= ' WHERE idFilm = '.$idFilm;
        $query .= ' ORDER BY assocFilmCinematographer.relevancePosition';

        return $this->fetchAll($query);
    }
}
