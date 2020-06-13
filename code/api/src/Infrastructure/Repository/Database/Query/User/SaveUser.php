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
        $query = 'INSERT INTO user (`idUser`, `name`, `cookieFilmaffinity`, `dateAdded`)';
        $query .= ' VALUES (';
        $query .= $userIdFilmaffinity;
        $query .= ' , ' . $this->quote($userNameFilmaffinity);
        $query .= ' , ' . $this->quote($cookieFilmaffinity);
        $query .= ' , UNIX_TIMESTAMP()';
        $query .= ')';

        return $this->insertAndGetLastInsertedId($query);
    }
}
