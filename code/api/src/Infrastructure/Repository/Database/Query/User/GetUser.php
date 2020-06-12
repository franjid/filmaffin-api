<?php

namespace App\Infrastructure\Repository\Database\Query\User;

use App\Infrastructure\Component\Db\GlobalReadQuery;

class GetUser extends GlobalReadQuery
{
    public function getResult(int $userIdFilmaffinity): array
    {
        $query = 'SELECT';
        $query .= '   idUser';
        $query .= ' , name';
        $query .= ' , cookieFilmaffinity';
        $query .= ' FROM';
        $query .= ' user';
        $query .= ' WHERE idUser = ' . $userIdFilmaffinity;

        return $this->fetchAll($query);
    }
}
