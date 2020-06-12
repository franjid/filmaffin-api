<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\Collection\FilmCollection;

interface FilmIndexRepositoryInterface
{
    public function searchFilms(string $title): FilmCollection;
    public function getFilm(string $idFilmList): FilmCollection;
    public function getPopularFilms(int $numResults, int $offset): FilmCollection;
    public function getFilmsInTheatres(string $sortBy): FilmCollection;
}
