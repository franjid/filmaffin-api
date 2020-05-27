<?php

namespace App\Infrastructure\Repository\Database;

use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmActors;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmCinematographers;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmDirectors;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmMusicians;
use App\Infrastructure\Repository\Database\Query\Film\GetFilms;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmScreenplayers;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmTopics;
use App\Infrastructure\Repository\RepositoryAbstract;

class FilmDatabaseMysqlRepository extends RepositoryAbstract implements FilmDatabaseRepositoryInterface
{
    public function getFilms(int $offset, int $limit): array
    {
        /** @var GetFilms $query */
        $query = $this->getQuery(GetFilms::class);

        return $query->getResult($offset, $limit);
    }

    public function getFilmDirectors(int $idFilm): array
    {
        /** @var GetFilmDirectors $query */
        $query = $this->getQuery(GetFilmDirectors::class);

        return $query->getResult($idFilm);
    }

    public function getFilmActors(int $idFilm): array
    {
        /** @var GetFilmActors $query */
        $query = $this->getQuery(GetFilmActors::class);

        return $query->getResult($idFilm);
    }

    public function getFilmScreenplayers(int $idFilm): array
    {
        /** @var GetFilmScreenplayers $query */
        $query = $this->getQuery(GetFilmScreenplayers::class);

        return $query->getResult($idFilm);
    }

    public function getFilmMusicians(int $idFilm): array
    {
        /** @var GetFilmMusicians $query */
        $query = $this->getQuery(GetFilmMusicians::class);

        return $query->getResult($idFilm);
    }

    public function getFilmCinematographers(int $idFilm): array
    {
        /** @var GetFilmCinematographers $query */
        $query = $this->getQuery(GetFilmCinematographers::class);

        return $query->getResult($idFilm);
    }

    public function getFilmTopics(int $idFilm): array
    {
        /** @var GetFilmTopics $query */
        $query = $this->getQuery(GetFilmTopics::class);

        return $query->getResult($idFilm);
    }
}
