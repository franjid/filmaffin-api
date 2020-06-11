<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\FilmParticipant;
use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmMusicians extends GlobalReadQuery
{
    public function getResult(int $idFilm): FilmParticipantCollection
    {
        $query = 'SELECT';
        $query .= ' musician.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' JOIN assocFilmMusician USING(idFilm)';
        $query .= ' JOIN musician USING(idMusician)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmMusician.relevancePosition';

        $results = $this->fetchAll($query);
        if (!$results) {
            return new FilmParticipantCollection();
        }

        $musicians = [];
        foreach ($results as $result) {
            $musicians[] = FilmParticipant::buildFromArray($result);
        }

        return new FilmParticipantCollection(...$musicians);
    }
}
