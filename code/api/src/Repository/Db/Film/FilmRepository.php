<?php

namespace App\Repository\Db\Film;

use App\Query\Db\Film\GetFilmActors;
use App\Query\Db\Film\GetFilmCinematographers;
use App\Query\Db\Film\GetFilmDirectors;
use App\Query\Db\Film\GetFilmExtraInfo;
use App\Query\Db\Film\GetFilmMusicians;
use App\Query\Db\Film\GetFilms;
use App\Query\Db\Film\GetFilmScreenplayers;
use App\Query\Db\Film\GetFilmTopics;
use App\Repository\RepositoryAbstract;

class FilmRepository extends RepositoryAbstract implements FilmRepositoryInterface
{
    public function getFilms(int $offset, int $limit): array
    {
        /** @var GetFilms $query */
        $query = $this->getQuery(GetFilms::class);

        return $query->getResult($offset, $limit);
    }

    public function getFilmExtraInfo(array $idFilms): array
    {
        /** @var GetFilmExtraInfo $query */
        $query = $this->getQuery(GetFilmExtraInfo::class);

        return $query->getResult($idFilms);
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
