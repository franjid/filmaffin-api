<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\Film;
use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFrequentlyUpdatedFilms extends GlobalReadQuery
{
    public function getResult(): FilmCollection
    {
        $query = 'SELECT';
        $query .= '   f.idFilm';
        $query .= ' , title';
        $query .= ' , originalTitle';
        $query .= ' , rating';
        $query .= ' , numRatings';
        $query .= ' , year';
        $query .= ' , duration';
        $query .= ' , country';
        $query .= ' , synopsis';
        $query .= ' , fp.ranking AS popularityRanking';
        $query .= ' , IF (fit.releaseDate IS NOT NULL, 1, 0) AS inTheatres';
        $query .= ' , fit.releaseDate';
        $query .= ' FROM';
        $query .= ' film f';
        $query .= ' LEFT JOIN filmPopular fp USING(idFilm)';
        $query .= ' LEFT JOIN filmInTheatres fit USING(idFilm)';
        $query .= ' WHERE fp.ranking IS NOT NULL OR fit.releaseDate IS NOT NULL';

        $result = $this->fetchAllObject($query, Film::class);

        return new FilmCollection(...$result);
    }
}
