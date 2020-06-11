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
        $query .= ' JOIN assocFilmCinematographer USING(idFilm)';
        $query .= ' JOIN cinematographer USING(idCinematographer)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmCinematographer.relevancePosition';

        $results = $this->fetchAll($query);
        if (!$results) {
            return new FilmParticipantCollection();
        }

        $cinematographers = [];
        foreach ($results as $result) {
            $cinematographers[] = FilmParticipant::buildFromArray($result);
        }

        return new FilmParticipantCollection(...$cinematographers);
    }
}
