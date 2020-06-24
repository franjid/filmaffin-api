<?php

namespace App\Infrastructure\Repository\Database\Query\User;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetLastIdUserRatingFromUserFriends extends GlobalReadQuery
{
    public function getResult(int $idUser): array
    {
        $query = 'SELECT';
        $query .= '   ur.idUserRating';
        $query .= ' FROM';
        $query .= ' userRating ur';
        $query .= ' JOIN userFriendship uf ON uf.idUserTarget = ur.idUser';
        $query .= ' WHERE uf.idUserSource = ' . $idUser;
        $query .= ' ORDER BY idUserRating DESC';
        $query .= ' LIMIT 1';

        return $this->fetchAll($query);
    }
}
