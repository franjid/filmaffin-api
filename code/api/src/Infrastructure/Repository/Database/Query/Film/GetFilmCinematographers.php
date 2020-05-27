<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\FilmParticipant;
use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmCinematographers extends GlobalReadQuery
{
    public function getResult(int $idFilm): FilmParticipantCollection
    {
        $query = 'SELECT';
        $query .= ' cinematographer.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' LEFT JOIN assocFilmCinematographer USING(idFilm)';
        $query .= ' LEFT JOIN cinematographer USING(idCinematographer)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmCinematographer.relevancePosition';

        $result = $this->fetchAllObject($query, FilmParticipant::class);

        return new FilmParticipantCollection(...$result);
    }
}
