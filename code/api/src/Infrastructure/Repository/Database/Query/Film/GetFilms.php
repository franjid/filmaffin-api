<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilms extends GlobalReadQuery
{
    public function getResult(int $offset, int $limit): array
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
        $query .= ' , proReviews';
        $query .= ' , filmPopular.ranking AS popularityRanking';
        $query .= ' , IF (filmInTheatres.releaseDate IS NOT NULL, 1, 0) AS inTheatres';
        $query .= ' , filmInTheatres.releaseDate';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' LEFT JOIN filmPopular USING(idFilm)';
        $query .= ' LEFT JOIN filmInTheatres USING(idFilm)';
        $query .= ' GROUP BY idFilm';
        $query .= ' LIMIT ' . $offset . ', ' . $limit;

        return $this->fetchAll($query);
    }
}
