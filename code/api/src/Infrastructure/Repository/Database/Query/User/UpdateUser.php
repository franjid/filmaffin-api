<?php

namespace App\Infrastructure\Repository\Database\Query\User;

use App\Infrastructure\Component\Db\GlobalWriteQuery;

class UpdateUser extends GlobalWriteQuery
{
    public function getResult(
        int $userIdFilmaffinity,
        ?string $userNameFilmaffinity,
        ?string $cookieFilmaffinity
    ): int
    {
        $query = 'UPDATE user';
        $query .= ' SET';

        if ($userNameFilmaffinity) {
            $query .= ' name = ' . $this->quote($userNameFilmaffinity) . ', ';
        }

        if ($cookieFilmaffinity) {
            $query .= ' cookieFilmaffinity = ' . $this->quote($cookieFilmaffinity) . ', ';
        }

        $query .= ' dateUpdated = UNIX_TIMESTAMP()';
        $query .= ' WHERE idUser = ' . $userIdFilmaffinity;

        return $this->executeUpdate($query);
    }
}
