<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmMusicians extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .=  ' musician.name';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LEFT JOIN assocFilmMusician USING(idFilm)';
        $query .= ' LEFT JOIN musician USING(idMusician)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmMusician.relevancePosition';

        return $this->fetchAll($query);
    }
}
