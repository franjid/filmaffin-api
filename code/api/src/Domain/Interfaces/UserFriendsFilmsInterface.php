<?php

namespace App\Domain\Interfaces;

use App\Domain\Entity\Collection\FilmRatedByUserExtendedCollection;

interface UserFriendsFilmsInterface
{
    public function getUserFriendsFilms(
        int $idUser,
        int $numResults,
        int $offset
    ): FilmRatedByUserExtendedCollection;
}
