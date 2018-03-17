<?php

namespace Query\Db\Film;

use Component\Db\GlobalReadQuery;
use Entity\Film;

class GetFilms extends GlobalReadQuery
{
    const DIC_NAME = 'Query.Db.Film.GetFilms';

    /**
     * @param int $offset
     * @param int $limit
     * @return Film[]
     */
    public function getResult($offset, $limit)
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
        $query .=  ' , GROUP_CONCAT(DISTINCT director.name ORDER BY idAssocFilmDirector) AS directors';
        $query .=  ' , GROUP_CONCAT(DISTINCT actor.name ORDER BY idAssocFilmActor) AS actors';
        $query .=  ' , filmPopular.ranking AS popularityRanking';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LEFT JOIN assocFilmDirector USING(idFilm)';
        $query .= ' LEFT JOIN director USING(idDirector)';
        $query .= ' LEFT JOIN assocFilmActor USING(idFilm)';
        $query .= ' LEFT JOIN actor USING(idActor)';
        $query .= ' LEFT JOIN filmPopular USING(idFilm)';
        $query .= ' GROUP BY idFilm';
        $query .= ' LIMIT ' . (int) $offset . ', ' . (int) $limit;

        return $this->fetchAllObject($query, Film::class);
    }
}
