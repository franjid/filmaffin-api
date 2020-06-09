<?php

namespace App\Infrastructure\Repository\Database\Query\User;

use App\Infrastructure\Component\Db\GlobalWriteQuery;

class SaveUser extends GlobalWriteQuery
{
    public function getResult(
        int $userIdFilmaffinity,
        string $userNameFilmaffinity,
        string $cookieFilmaffinity
    ): int
    {
        $query = 'INSERT INTO user (`idUser`, `name`, `cookieFilmaffinity`)';
        $query .= ' VALUES (';
        $query .= $userIdFilmaffinity;
        $query .= ' , ' . $this->quote($userNameFilmaffinity);
        $query .= ' , ' . $this->quote($cookieFilmaffinity);
        $query .= ')';

        return $this->insertAndGetLastInsertedId($query);
    }
}
