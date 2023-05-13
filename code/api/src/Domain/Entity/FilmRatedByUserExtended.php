<?php

namespace App\Domain\Entity;

/**
 * The "extension" refers to the film
 * In this one the entity stores Film type instead of the idFilm (as int).
 */
class FilmRatedByUserExtended
{
    final public const FIELD_FILM = 'film';
    final public const FIELD_USER = 'user';
    final public const FIELD_USER_RATING = 'userRating';
    final public const FIELD_DATE_RATED = 'dateRated';

    public function __construct(private readonly Film $film, private readonly UserFilmaffinity $user, private readonly int $userRating, private readonly \DateTimeImmutable $dateRated)
    {
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

    public function getDateRated(): \DateTimeImmutable
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
