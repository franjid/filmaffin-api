<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\Collection\FilmCollection;

interface FilmIndexRepositoryInterface
{
    public function searchFilms(string $title): FilmCollection;

    public function getFilm(string $idFilmList, bool $includeReviews = true): FilmCollection;

    public function getPopularFilms(int $numResults, int $offset): FilmCollection;

    public function getFilmsInTheatres(int $numResults, string $sortBy): FilmCollection;

    public function getNewFilmsInPlatform(string $platform, int $numResults): FilmCollection;

    public function searchFilmsByTeamMember(
        string $teamMemberType,
        string $teamMemberName,
        string $sortBy,
        int $numResults,
        int $offset
    ): FilmCollection;
}
