<?php

namespace App\Query\Db\Film;

use App\Component\Db\GlobalReadQuery;
use App\Entity\FilmExtraInfo;

class GetFilmExtraInfo extends GlobalReadQuery
{
    /**
     * @param array $idFilms
     *
     * @return FilmExtraInfo[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getResult(array $idFilms): array
    {
        $query = 'SELECT';
        $query .=  '   idFilm';
        $query .=  ' , GROUP_CONCAT(DISTINCT director.name ORDER BY idAssocFilmDirector) AS directors';
        $query .=  ' , GROUP_CONCAT(DISTINCT actor.name ORDER BY idAssocFilmActor) AS actors';
        $query .=  ' , CONCAT(';
        $query .=  '     GROUP_CONCAT(DISTINCT genre.name order by idAssocFilmGenre)';
        $query .=  '     , ","';
        $query .=  '     , GROUP_CONCAT(DISTINCT topic.name order by idAssocFilmTopic)';
        $query .=  '   ) AS topics';
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
        $query .= ' WHERE idFilm in(' . implode(',', $idFilms) . ')';
        $query .= ' GROUP BY idFilm';

        return $this->fetchAllObject($query, FilmExtraInfo::class);
    }
}
