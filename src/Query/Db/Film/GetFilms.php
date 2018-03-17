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
        $query .=  ' , CONCAT(';
        $query .=  '     GROUP_CONCAT(DISTINCT genre.name order by idAssocFilmGenre)';
        $query .=  '     , ","';
        $query .=  '     , GROUP_CONCAT(DISTINCT topic.name order by idAssocFilmTopic)';
        $query .=  '   ) AS topics';
        $query .=  ' , filmPopular.ranking AS popularityRanking';
        $query .=  ' , synopsis';
        $query .=  ' , GROUP_CONCAT(DISTINCT screenplayer.name ORDER BY idAssocFilmScreenplayer) AS screenplayers';
        $query .=  ' , GROUP_CONCAT(DISTINCT musician.name ORDER BY idAssocFilmMusician) AS musicians';
        $query .=  ' , GROUP_CONCAT(DISTINCT cinematographer.name ORDER BY idAssocFilmCinematographer) AS cinematographers';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LEFT JOIN assocFilmDirector USING(idFilm)';
        $query .= ' LEFT JOIN director USING(idDirector)';
        $query .= ' LEFT JOIN assocFilmActor USING(idFilm)';
        $query .= ' LEFT JOIN actor USING(idActor)';
        $query .= ' LEFT JOIN assocFilmGenre USING(idFilm)';
        $query .= ' LEFT JOIN genre USING(idGenre)';
        $query .= ' LEFT JOIN assocFilmTopic USING(idFilm)';
        $query .= ' LEFT JOIN topic USING(idTopic)';
        $query .= ' LEFT JOIN assocFilmScreenplayer USING(idFilm)';
        $query .= ' LEFT JOIN screenplayer USING(idScreenplayer)';
        $query .= ' LEFT JOIN assocFilmMusician USING(idFilm)';
        $query .= ' LEFT JOIN musician USING(idMusician)';
        $query .= ' LEFT JOIN assocFilmCinematographer USING(idFilm)';
        $query .= ' LEFT JOIN cinematographer USING(idCinematographer)';
        $query .= ' LEFT JOIN filmPopular USING(idFilm)';
        $query .= ' GROUP BY idFilm';
        $query .= ' LIMIT ' . (int) $offset . ', ' . (int) $limit;

        return $this->fetchAllObject($query, Film::class);
    }
}
