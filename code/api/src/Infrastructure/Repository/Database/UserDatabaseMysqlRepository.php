<?php

namespace App\Infrastructure\Repository\Database;

use App\Domain\Entity\UserFilmaffinity;
use App\Infrastructure\Exception\Database\UserNotFoundException;
use App\Infrastructure\Interfaces\UserDatabaseRepositoryInterface;
use App\Infrastructure\Repository\Database\Query\User\GetUser;
use App\Infrastructure\Repository\Database\Query\User\SaveUser;
use App\Infrastructure\Repository\Database\Query\User\UpdateUser;
use App\Infrastructure\Repository\RepositoryAbstract;

class UserDatabaseMysqlRepository extends RepositoryAbstract implements UserDatabaseRepositoryInterface
{
    public function saveUser(
        int $userIdFilmaffinity,
        string $userNameFilmaffinity,
        string $cookieFilmaffinity,
        ?string $appNotificationsToken
    ): int
    {
        /** @var SaveUser $query */
        $query = $this->getQuery(SaveUser::class);

        return $query->getResult(
            $userIdFilmaffinity,
            $userNameFilmaffinity,
            $cookieFilmaffinity,
            $appNotificationsToken
        );
    }

    public function updateUser(
        int $userIdFilmaffinity,
        ?string $userNameFilmaffinity,
        ?string $cookieFilmaffinity,
        ?string $appNotificationsToken
    ): int
    {
        /** @var UpdateUser $query */
        $query = $this->getQuery(UpdateUser::class);

        return $query->getResult(
            $userIdFilmaffinity,
            $userNameFilmaffinity,
            $cookieFilmaffinity,
            $appNotificationsToken
        );
    }

    /**
     * @param int $userIdFilmaffinity
     *
     * @return UserFilmaffinity
     * @throws UserNotFoundException
     */
    public function getUser(int $userIdFilmaffinity): UserFilmaffinity
    {
        /** @var GetUser $query */
        $query = $this->getQuery(GetUser::class);
        $result = $query->getResult($userIdFilmaffinity);

        if (!$result) {
            throw new UserNotFoundException('User id not found: ' . $userIdFilmaffinity);
        }

        $result = array_shift($result);

        return new UserFilmaffinity(
            $result['idUser'],
            $result['name'],
            $result['cookieFilmaffinity'] !== '' ? $result['cookieFilmaffinity'] : null
        );
    }
}
