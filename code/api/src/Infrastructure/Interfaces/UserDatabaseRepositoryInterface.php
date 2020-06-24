<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\Collection\UserFilmaffinityCollection;
use App\Domain\Entity\UserFilmaffinity;
use App\Infrastructure\Exception\Database\UserNotFoundException;

interface UserDatabaseRepositoryInterface
{
    public function saveUser(
        int $userIdFilmaffinity,
        string $userNameFilmaffinity,
        string $cookieFilmaffinity,
        ?string $appNotificationsToken
    ): int;

    public function updateUser(
        int $userIdFilmaffinity,
        ?string $userNameFilmaffinity,
        ?string $cookieFilmaffinity,
        ?string $appNotificationsToken
    ): int;

    /**
     * @param int $userIdFilmaffinity
     *
     * @return UserFilmaffinity
     * @throws UserNotFoundException
     */
    public function getUser(int $userIdFilmaffinity): UserFilmaffinity;

    public function getUsersWithFriends(): UserFilmaffinityCollection;
    public function getLastIdUserRatingNotificated(int $userIdFilmaffinity): ?int;
    public function getLastIdUserRatingFromUserFriends(int $userIdFilmaffinity): ?int;
}
