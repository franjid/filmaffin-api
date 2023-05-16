<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmMusicians extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .= ' musician.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' JOIN assocFilmMusician USING(idFilm)';
        $query .= ' JOIN musician USING(idMusician)';
        $query .= ' WHERE idFilm = '.$idFilm;
        $query .= ' ORDER BY assocFilmMusician.relevancePosition';

        return $this->fetchAll($query);
    }
}
