<?php

namespace Repository\Index\Film;

interface FilmRepositoryInterface
{
    public function searchFilms(string $title): array;
    public function getFilm(int $idFilm): array;
    public function getPopularFilms(int $numResults, int $offset): array ;
    public function getFilmsInTheatres(string $sortBy): array;
}
