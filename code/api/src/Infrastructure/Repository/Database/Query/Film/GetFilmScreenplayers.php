<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmScreenplayers extends GlobalReadQuery
{
    public function getResult(int $idFilm): array
    {
        $query = 'SELECT';
        $query .= ' screenplayer.name';
        $query .= ' FROM';
        $query .= ' film';
        $query .= ' JOIN assocFilmScreenplayer USING(idFilm)';
        $query .= ' JOIN screenplayer USING(idScreenplayer)';
        $query .= ' WHERE idFilm = '.$idFilm;
        $query .= ' ORDER BY assocFilmScreenplayer.relevancePosition';

        return $this->fetchAll($query);
    }
}
