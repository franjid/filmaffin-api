<?php

namespace App\Query\Db\Film;

use App\Component\Db\GlobalReadQuery;

class GetFilmDirectors extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .=  ' director.name';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LEFT JOIN assocFilmDirector USING(idFilm)';
        $query .= ' LEFT JOIN director USING(idDirector)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmDirector.relevancePosition';

        return $this->fetchAll($query);
    }
}