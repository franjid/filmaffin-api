<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Component\Db\GlobalReadQuery;
use App\Domain\Entity\Film;

class GetFilms extends GlobalReadQuery
{
    /**
     * @param int $offset
     * @param int $limit
     *
     * @return Film[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getResult(int $offset, int $limit): array
    {
        $query = 'SELECT';
        $query .=  '   idFilm';
        $query .=  ' , title';
        $query .=  ' , originalTitle';
        $query .=  ' , rating';
        $query .=  ' , numRatings';
        $query .=  ' , year';
        $query .=  ' , duration';
        $query .=  ' , country';
        $query .=  ' , synopsis';
        $query .=  ' , filmPopular.ranking AS popularityRanking';
        $query .=  ' , IF (filmInTheatres.releaseDate IS NOT NULL, 1, 0) AS inTheatres';
        $query .=  ' , filmInTheatres.releaseDate';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LEFT JOIN filmPopular USING(idFilm)';
        $query .= ' LEFT JOIN filmInTheatres USING(idFilm)';
        $query .= ' GROUP BY idFilm';
        $query .= ' LIMIT ' . (int) $offset . ', ' . (int) $limit;

        return $this->fetchAllObject($query, Film::class);
    }
}
