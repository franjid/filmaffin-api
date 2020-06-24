<?php

namespace App\Infrastructure\Repository\Database\Query\User;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetUsersWithFriends extends GlobalReadQuery
{
    public function getResult(): array
    {
        $query = 'SELECT';
        $query .= '   u.idUser';
        $query .= ' , u.name';
        $query .= ' , u.cookieFilmaffinity';
        $query .= ' FROM';
        $query .= ' userFriendship uf';
        $query .= ' JOIN user u ON u.idUser = uf.idUserSource';
        $query .= ' GROUP BY idUserSource';

        return $this->fetchAll($query);
    }
}
