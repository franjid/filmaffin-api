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
        $query .= ' JOIN assocFilmActor USING(idFilm)';
        $query .= ' JOIN actor USING(idActor)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmActor.relevancePosition';

        $results = $this->fetchAll($query);
        if (!$results) {
            return new FilmParticipantCollection();
        }

        $actors = [];
        foreach ($results as $result) {
            $actors[] = FilmParticipant::buildFromArray($result);
        }

        return new FilmParticipantCollection(...$actors);
    }
}
