<?php

namespace App\Infrastructure\Repository\Database;

use App\Domain\Entity\UserFilmaffinity;
use App\Infrastructure\Exception\Database\UserNotFoundException;
use App\Infrastructure\Interfaces\UserDatabaseRepositoryInterface;
use App\Infrastructure\Repository\Database\Query\User\GetUser;
use App\Infrastructure\Repository\Database\Query\User\SaveUser;
use App\Infrastructure\Repository\RepositoryAbstract;
use Doctrine\DBAL\DBALException;

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

    /**
     * @param int $userIdFilmaffinity
     *
     * @return UserFilmaffinity
     * @throws UserNotFoundException
     * @throws DBALException
     */
    public function getUser(int $userIdFilmaffinity): UserFilmaffinity
    {
        /** @var GetUser $query */
        $query = $this->getQuery(GetUser::class);

        return $query->getResult($userIdFilmaffinity);
    }
}
