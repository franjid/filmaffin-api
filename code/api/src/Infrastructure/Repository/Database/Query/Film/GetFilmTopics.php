<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Domain\Entity\Collection\FilmAttributeCollection;
use App\Domain\Entity\FilmAttribute;
use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmTopics extends GlobalReadQuery
{
    public function getResult(int $idFilm): FilmAttributeCollection
    {
        $query = 'SELECT';
        $query .= ' topic.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' JOIN assocFilmTopic USING(idFilm)';
        $query .= ' JOIN topic USING(idTopic)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmTopic.relevancePosition';

        $results = $this->fetchAll($query);
        if (!$results) {
            return new FilmAttributeCollection();
        }

        $topics = [];
        foreach ($results as $result) {
            $topics[] = FilmAttribute::buildFromArray($result);
        }

        return new FilmAttributeCollection(...$topics);
    }
}
