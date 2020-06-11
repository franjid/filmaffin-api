<?php

namespace App\Domain\Entity;

use DateTimeImmutable;

class FilmRatedByUser
{
    public const FIELD_ID_FILM = 'idFilm';
    public const FIELD_USER = 'user';
    public const FIELD_USER_RATING = 'userRating';
    public const FIELD_DATE_RATED = 'dateRated';

    private int $idFilm;
    private UserFilmaffinity $user;
    private int $userRating;
    private DateTimeImmutable $dateRated;

    public function __construct(
        int $idFilm,
        UserFilmaffinity $user,
        int $userRating,
        DateTimeImmutable $dateRated
    )
    {
        $this->idFilm = $idFilm;
        $this->user = $user;
        $this->userRating = $userRating;
        $this->dateRated = $dateRated;
    }

    public function getIdFilm(): int
    {
        return $this->idFilm;
    }

    public function getUser(): UserFilmaffinity
    {
        return $this->user;
    }

    public function getUserRating(): int
    {
        return $this->userRating;
    }

    public function getDateRated(): DateTimeImmutable
    {
        return $this->dateRated;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_ID_FILM => $this->getIdFilm(),
            self::FIELD_USER => $this->getUser()->toArray(),
            self::FIELD_USER_RATING => $this->getUserRating(),
            self::FIELD_DATE_RATED => $this->getDateRated()->getTimestamp(),
        ];
    }
}
