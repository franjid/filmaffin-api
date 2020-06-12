<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmsById extends GlobalReadQuery
{
    public function getResult(array $idFilms): array
    {
        $query = 'SELECT';
        $query .= '   idFilm';
        $query .= ' , title';
        $query .= ' , originalTitle';
        $query .= ' , rating';
        $query .= ' , numRatings';
        $query .= ' , year';
        $query .= ' , duration';
        $query .= ' , country';
        $query .= ' , synopsis';
        $query .= ' , filmPopular.ranking AS popularityRanking';
        $query .= ' , IF (filmInTheatres.releaseDate IS NOT NULL, 1, 0) AS inTheatres';
        $query .= ' , filmInTheatres.releaseDate';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' LEFT JOIN filmPopular USING(idFilm)';
        $query .= ' LEFT JOIN filmInTheatres USING(idFilm)';
        $query .= ' WHERE idFilm IN (' . implode(',', $idFilms) . ')';
        $query .= ' GROUP BY idFilm';

        return $this->fetchAll($query);
    }
}
