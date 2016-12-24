<?php

namespace Query\Film;

use Component\Db\GlobalReadQuery;
use Entity\Film;

class GetFilms extends GlobalReadQuery
{
    const DIC_NAME = 'Query.Film.GetFilms';

    /**
     * @param int $offset
     * @param int $limit
     * @return Film[]
     */
    public function getResult($offset, $limit)
    {
        $query = 'SELECT';
        $query .=  ' idFilm';
        $query .=  ' , title';
        $query .=  ' , numRatings';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LIMIT ' . (int) $offset . ', ' . (int) $limit;

        return $this->fetchAllObject($query, Film::class);
    }
}
