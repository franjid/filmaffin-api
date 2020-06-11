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
        $query .= ' JOIN assocFilmScreenplayer USING(idFilm)';
        $query .= ' JOIN screenplayer USING(idScreenplayer)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmScreenplayer.relevancePosition';

        $results = $this->fetchAll($query);
        if (!$results) {
            return new FilmParticipantCollection();
        }

        $screenplayers = [];
        foreach ($results as $result) {
            $screenplayers[] = FilmParticipant::buildFromArray($result);
        }

        return new FilmParticipantCollection(...$screenplayers);
    }
}
