<?php

namespace App\Infrastructure\Interfaces;

interface UserDatabaseRepositoryInterface
{
    public function saveUser(int $userIdFilmaffinity, string $cookieFilmaffinity): int;
}
