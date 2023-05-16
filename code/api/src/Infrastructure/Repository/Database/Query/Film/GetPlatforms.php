<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetPlatforms extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .= ' p.type';
        $query .= ' , p.name';
        $query .= ' FROM';
        $query .= ' assocFilmPlatform afp';
        $query .= ' JOIN platform p USING(idPlatform)';
        $query .= ' WHERE afp.idFilm = '.$idFilm;
        $query .= ' ORDER BY afp.relevancePosition ASC';

        return $this->fetchAll($query);
    }
}
