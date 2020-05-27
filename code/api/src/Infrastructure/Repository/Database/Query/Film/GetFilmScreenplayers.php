<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\FilmParticipant;
use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmScreenplayers extends GlobalReadQuery
{
    public function getResult(int $idFilm): FilmParticipantCollection
    {
        $query = 'SELECT';
        $query .= ' screenplayer.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' LEFT JOIN assocFilmScreenplayer USING(idFilm)';
        $query .= ' LEFT JOIN screenplayer USING(idScreenplayer)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmScreenplayer.relevancePosition';

        $result = $this->fetchAllObject($query, FilmParticipant::class);

        return new FilmParticipantCollection(...$result);
    }
}
