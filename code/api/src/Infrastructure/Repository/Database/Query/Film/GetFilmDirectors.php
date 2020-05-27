<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\FilmParticipant;
use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmDirectors extends GlobalReadQuery
{
    public function getResult(int $idFilm): FilmParticipantCollection
    {
        $query = 'SELECT';
        $query .= ' director.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' LEFT JOIN assocFilmDirector USING(idFilm)';
        $query .= ' LEFT JOIN director USING(idDirector)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmDirector.relevancePosition';

        $result = $this->fetchAllObject($query, FilmParticipant::class);

        return new FilmParticipantCollection(...$result);
    }
}
