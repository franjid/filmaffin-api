<?php

namespace App\Query\Db\Film;

use App\Component\Db\GlobalReadQuery;

class GetFilmCinematographers extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .=  ' cinematographer.name';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LEFT JOIN assocFilmCinematographer USING(idFilm)';
        $query .= ' LEFT JOIN cinematographer USING(idCinematographer)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmCinematographer.relevancePosition';

        return $this->fetchAll($query);
    }
}
