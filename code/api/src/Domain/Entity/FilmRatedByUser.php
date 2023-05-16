<?php

namespace App\Domain\Entity;

class FilmRatedByUser
{
    final public const FIELD_ID_USER_RATING = 'idUserRating';
    final public const FIELD_ID_FILM = 'idFilm';
    final public const FIELD_USER = 'user';
    final public const FIELD_USER_RATING = 'userRating';
    final public const FIELD_DATE_RATED = 'dateRated';

    public function __construct(
        private readonly int $idUserRating,
        private readonly int $idFilm,
        private readonly UserFilmaffinity $user,
        private readonly int $userRating,
        private readonly \DateTimeImmutable $dateRated
    ) {
    }

    public function getIdUserRating(): int
    {
        return $this->idUserRating;
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

    public function getDateRated(): \DateTimeImmutable
    {
        return $this->dateRated;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_ID_USER_RATING => $this->getIdUserRating(),
            self::FIELD_ID_FILM => $this->getIdFilm(),
            self::FIELD_USER => $this->getUser()->toArray(),
            self::FIELD_USER_RATING => $this->getUserRating(),
            self::FIELD_DATE_RATED => $this->getDateRated()->getTimestamp(),
        ];
    }
}
