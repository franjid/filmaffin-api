<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\UserFilmaffinity;
use App\Infrastructure\Exception\Database\UserNotFoundException;

interface UserDatabaseRepositoryInterface
{
    public function saveUser(
        int $userIdFilmaffinity,
        string $userNameFilmaffinity,
        string $cookieFilmaffinity
    ): int;

    /**
     * @param int $userIdFilmaffinity
     *
     * @return UserFilmaffinity
     * @throws UserNotFoundException
     */
    public function getUser(int $userIdFilmaffinity): UserFilmaffinity;
}
