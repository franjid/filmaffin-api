<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\Film;
use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilms extends GlobalReadQuery
{
    public function getResult(int $offset, int $limit): FilmCollection
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
        $query .= ' GROUP BY idFilm';
        $query .= ' LIMIT ' . $offset . ', ' . $limit;

        $results = $this->fetchAll($query);

        if (!$results) {
            return new FilmCollection();
        }

        $films = [];

        foreach ($results as $result) {
            $films[] = Film::buildFromArray($result);
        }

        return new FilmCollection(...$films);
    }
}
