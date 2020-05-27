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
        $query .= ' LEFT JOIN assocFilmTopic USING(idFilm)';
        $query .= ' LEFT JOIN topic USING(idTopic)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmTopic.relevancePosition';

        $result = $this->fetchAllObject($query, FilmAttribute::class);

        return new FilmAttributeCollection(...$result);
    }
}
