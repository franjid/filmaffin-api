<?php

namespace App\Infrastructure\Repository\Database;

use App\Infrastructure\Interfaces\UserDatabaseRepositoryInterface;
use App\Infrastructure\Repository\Database\Query\User\SaveUser;
use App\Infrastructure\Repository\RepositoryAbstract;

class UserDatabaseMysqlRepository extends RepositoryAbstract implements UserDatabaseRepositoryInterface
{
    public function saveUser(
        int $userIdFilmaffinity,
        string $userNameFilmaffinity,
        string $cookieFilmaffinity
    ): int
    {
        /** @var SaveUser $query */
        $query = $this->getQuery(SaveUser::class);

        return $query->getResult($userIdFilmaffinity, $userNameFilmaffinity, $cookieFilmaffinity);
    }
}
