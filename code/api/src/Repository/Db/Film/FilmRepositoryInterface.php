<?php

namespace App\Repository\Db\Film;

use App\Entity\Film;
use App\Entity\FilmExtraInfo;

interface FilmRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @return Film[]
     */
    public function getFilms(int $offset, int $limit): array;

    /**
     * @param array $idFilms
     * @return FilmExtraInfo[]
     */
    public function getFilmExtraInfo(array $idFilms): array;

    public function getFilmDirectors(int $idFilm): array;
    public function getFilmActors(int $idFilm): array;
    public function getFilmScreenplayers(int $idFilm): array;
    public function getFilmMusicians(int $idFilm): array;
    public function getFilmCinematographers(int $idFilm): array;
    public function getFilmTopics(int $idFilm): array;
}
