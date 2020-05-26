<?php

namespace App\Infrastructure\Repository\Index;

use App\Infrastructure\Interfaces\FilmIndexRepositoryInterface;
use App\Infrastructure\Repository\Index\Query\Film\GetFilm;
use App\Infrastructure\Repository\Index\Query\Film\GetFilmsInTheatres;
use App\Infrastructure\Repository\Index\Query\Film\GetPopularFilms;
use App\Infrastructure\Repository\Index\Query\Film\SearchFilms;
use App\Infrastructure\Repository\RepositoryAbstract;

class FilmIndexElasticsearchRepository extends RepositoryAbstract implements FilmIndexRepositoryInterface
{
    public function searchFilms(string $title): array
    {
        /** @var SearchFilms $query */
        $query = $this->getQuery(SearchFilms::class);

        return $query->getResult($title);
    }

    public function getFilm(int $idFilm): array
    {
        /** @var GetFilm $query */
        $query = $this->getQuery(GetFilm::class);

        return $query->getResult($idFilm);
    }

    public function getPopularFilms(int $numResults, int $offset): array
    {
        /** @var GetPopularFilms $query */
        $query = $this->getQuery(GetPopularFilms::class);

        return $query->getResult($numResults, $offset);
    }

    public function getFilmsInTheatres(string $sortBy): array
    {
        /** @var GetFilmsInTheatres $query */
        $query = $this->getQuery(GetFilmsInTheatres::class);

        return $query->getResult($sortBy);
    }
}