<?php

namespace App\Infrastructure\Repository\Database\Query\User;

use App\Infrastructure\Component\Db\GlobalWriteQuery;

class SaveUser extends GlobalWriteQuery
{
    public function getResult(
        int $userIdFilmaffinity,
        string $userNameFilmaffinity,
        string $cookieFilmaffinity,
        ?string $appNotificationsToken
    ): int {
        $query = 'INSERT INTO user (';
        $query .= '  `idUser`';
        $query .= ' , `name`';
        $query .= ' , `cookieFilmaffinity`';

        if ($appNotificationsToken && $appNotificationsToken !== 'null') {
            $query .= ' , `appNotificationsToken`';
        }

        $query .= ' , `dateAdded`';
        $query .= ')';

        $query .= ' VALUES (';
        $query .= $userIdFilmaffinity;
        $query .= ' , '.$this->quote($userNameFilmaffinity);
        $query .= ' , '.$this->quote($cookieFilmaffinity);

        if ($appNotificationsToken && $appNotificationsToken !== 'null') {
            $query .= ' , '.$this->quote($appNotificationsToken);
        }

        $query .= ' , UNIX_TIMESTAMP()';
        $query .= ')';

        return $this->insertAndGetLastInsertedId($query);
    }
}
