<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\UserFilmaffinity;

interface FilmaffinityRepositoryInterface
{
    public function loginUser(string $user, string $password): UserFilmaffinity;
}
