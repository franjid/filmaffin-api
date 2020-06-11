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
        $query .= ' JOIN assocFilmDirector USING(idFilm)';
        $query .= ' JOIN director USING(idDirector)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmDirector.relevancePosition';

        $results = $this->fetchAll($query);
        if (!$results) {
            return new FilmParticipantCollection();
        }

        $directors = [];
        foreach ($results as $result) {
            $directors[] = FilmParticipant::buildFromArray($result);
        }

        return new FilmParticipantCollection(...$directors);
    }
}
