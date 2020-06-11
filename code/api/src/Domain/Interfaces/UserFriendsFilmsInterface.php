<?php

namespace App\Domain\Interfaces;

use App\Domain\Entity\Collection\FilmRatedByUserExtendedCollection;
use App\Domain\Exception\UserNotFoundException;

interface UserFriendsFilmsInterface
{
    /**
     * @param int $idUser
     * @param int $numResults
     * @param int $offset
     *
     * @return FilmRatedByUserExtendedCollection
     */
    public function getUserFriendsFilms(
        int $idUser,
        int $numResults,
        int $offset
    ): FilmRatedByUserExtendedCollection;
}
