<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmDirectors extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .= ' director.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' JOIN assocFilmDirector USING(idFilm)';
        $query .= ' JOIN director USING(idDirector)';
        $query .= ' WHERE idFilm = '.$idFilm;
        $query .= ' ORDER BY assocFilmDirector.relevancePosition';

        return $this->fetchAll($query);
    }
}
