<?php

namespace App\Infrastructure\Repository\Database\Query\Film;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetFilmsRatedByUserFriends extends GlobalReadQuery
{
    public function getResult(
        int $idUser,
        int $numResults,
        int $offset
    ): array
    {
        $query = 'SELECT';
        $query .= '   ur.idUserRating';
        $query .= ' , f.idFilm';
        $query .= ' , u.idUser';
        $query .= ' , u.name';
        $query .= ' , ur.rating';
        $query .= ' , ur.dateRated';
        $query .= ' FROM';
        $query .= ' userRating ur';
        $query .= ' JOIN film f USING(idFilm)';
        $query .= ' JOIN userFriendship uf ON uf.idUserTarget = ur.idUser';
        $query .= ' JOIN user u ON u.idUser = uf.idUserTarget';
        $query .= ' WHERE uf.idUserSource = ' . $idUser;
        $query .= ' ORDER BY ur.dateRated DESC, ur.idUserRating DESC';
        $query .= ' LIMIT ' . $offset . ', ' . $numResults;

        return $this->fetchAll($query);
    }
}
