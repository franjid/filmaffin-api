<?php

namespace Query\Db\Film;

use Component\Db\GlobalReadQuery;
use Entity\Film;

class GetFilms extends GlobalReadQuery
{
    public const DIC_NAME = 'Query.Db.Film.GetFilms';

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
