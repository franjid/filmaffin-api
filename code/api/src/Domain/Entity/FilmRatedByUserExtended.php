<?php

namespace App\Domain\Entity;

use DateTimeImmutable;

/**
 * The "extension" refers to the film
 * In this one the entity stores Film type instead of the idFilm (as int)
 */
class FilmRatedByUserExtended
{
    public const FIELD_FILM = 'film';
    public const FIELD_USER = 'user';
    public const FIELD_USER_RATING = 'userRating';
    public const FIELD_DATE_RATED = 'dateRated';

    private Film $film;
    private UserFilmaffinity $user;
    private int $userRating;
    private DateTimeImmutable $dateRated;

    public function __construct(
        Film $film,
        UserFilmaffinity $user,
        int $userRating,
        DateTimeImmutable $dateRated
    )
    {
        $this->film = $film;
        $this->user = $user;
        $this->userRating = $userRating;
        $this->dateRated = $dateRated;
    }

    public function getFilm(): Film
    {
        return $this->film;
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
            self::FIELD_FILM => $this->getFilm()->toArray(),
            self::FIELD_USER => $this->getUser()->toArray(),
            self::FIELD_USER_RATING => $this->getUserRating(),
            self::FIELD_DATE_RATED => $this->getDateRated()->getTimestamp(),
        ];
    }
}
