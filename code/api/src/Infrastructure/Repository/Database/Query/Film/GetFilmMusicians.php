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
        $query .= ' LEFT JOIN assocFilmMusician USING(idFilm)';
        $query .= ' LEFT JOIN musician USING(idMusician)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmMusician.relevancePosition';

        $result = $this->fetchAllObject($query, FilmParticipant::class);

        return new FilmParticipantCollection(...$result);
    }
}
