<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFrequentlyUpdatedFilms extends GlobalReadQuery
{
    public function getResult(): array
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
        $query .= ' , proReviews';
        $query .= ' , numFrames';
        $query .= ' , fp.ranking AS popularityRanking';
        $query .= ' , IF (fit.releaseDate IS NOT NULL, 1, 0) AS inTheatres';
        $query .= ' , IF (fit.releaseDate IS NOT NULL, fit.releaseDate, nfip.releaseDate) AS releaseDate';
        $query .= ' , nfip.platform AS newInPlatform';
        $query .= ' FROM';
        $query .= ' film f';
        $query .= ' LEFT JOIN filmPopular fp USING(idFilm)';
        $query .= ' LEFT JOIN filmInTheatres fit USING(idFilm)';
        $query .= ' LEFT JOIN newFilmsInPlatform nfip USING(idFilm)';
        $query .= ' WHERE fp.ranking IS NOT NULL OR fit.releaseDate IS NOT NULL OR nfip.releaseDate IS NOT NULL';

        return $this->fetchAll($query);
    }
}
