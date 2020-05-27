<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\FilmParticipant;
use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmActors extends GlobalReadQuery
{
    public function getResult(int $idFilm): FilmParticipantCollection
    {
        $query = 'SELECT';
        $query .= ' actor.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' LEFT JOIN assocFilmActor USING(idFilm)';
        $query .= ' LEFT JOIN actor USING(idActor)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmActor.relevancePosition';

        $result = $this->fetchAllObject($query, FilmParticipant::class);

        return new FilmParticipantCollection(...$result);
    }
}
