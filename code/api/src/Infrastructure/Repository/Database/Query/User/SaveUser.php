<?php

namespace App\Infrastructure\Repository\Database\Query\User;

use App\Infrastructure\Component\Db\GlobalWriteQuery;

class SaveUser extends GlobalWriteQuery
{
    public function getResult(int $userIdFilmaffinity, string $cookieFilmaffinity): int
    {
        $query = 'INSERT INTO user (`idUserFilmaffinity`, `cookieFilmaffinity`)';
        $query .= ' VALUES (';
        $query .= $userIdFilmaffinity;
        $query .= ' , ' . $this->quote($cookieFilmaffinity);
        $query .= ')';

        return $this->insertAndGetLastInsertedId($query);
    }
}
