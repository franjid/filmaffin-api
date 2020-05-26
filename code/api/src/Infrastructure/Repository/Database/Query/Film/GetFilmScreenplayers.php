<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Component\Db\GlobalReadQuery;

class GetFilmScreenplayers extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .=  ' screenplayer.name';
        $query .= ' FROM';
        $query .=  ' film';
        $query .= ' LEFT JOIN assocFilmScreenplayer USING(idFilm)';
        $query .= ' LEFT JOIN screenplayer USING(idScreenplayer)';
        $query .= ' WHERE idFilm = ' . $idFilm;
        $query .= ' ORDER BY assocFilmScreenplayer.relevancePosition';

        return $this->fetchAll($query);
    }
}
