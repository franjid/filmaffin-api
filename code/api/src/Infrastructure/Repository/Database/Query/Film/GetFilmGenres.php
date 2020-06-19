<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmGenres extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .= ' genre.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' JOIN assocFilmGenre USING(idFilm)';
        $query .= ' JOIN genre USING(idGenre)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmGenre.relevancePosition';

        return $this->fetchAll($query);
    }
}
