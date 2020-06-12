<?php

namespace App\Infrastructure\Repository\Index;

use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\Film;
use App\Infrastructure\Interfaces\FilmIndexRepositoryInterface;
use App\Infrastructure\Repository\Index\Query\Film\GetFilm;
use App\Infrastructure\Repository\Index\Query\Film\GetFilmsInTheatres;
use App\Infrastructure\Repository\Index\Query\Film\GetPopularFilms;
use App\Infrastructure\Repository\Index\Query\Film\SearchFilms;
use App\Infrastructure\Repository\RepositoryAbstract;

class FilmIndexElasticsearchRepository extends RepositoryAbstract implements FilmIndexRepositoryInterface
{
    public function searchFilms(string $title): FilmCollection
    {
        /** @var SearchFilms $query */
        $query = $this->getQuery(SearchFilms::class);
        $results = $query->getResult($title);

        return $this->populateFilmCollectionFromResults($results);
    }

    public function getFilm(string $idFilmList): FilmCollection
    {
        /** @var GetFilm $query */
        $query = $this->getQuery(GetFilm::class);
        $results = $query->getResult($idFilmList);

        return $this->populateFilmCollectionFromResults($results);
    }

    public function getPopularFilms(int $numResults, int $offset): FilmCollection
    {
        /** @var GetPopularFilms $query */
        $query = $this->getQuery(GetPopularFilms::class);
        $results = $query->getResult($numResults, $offset);

        return $this->populateFilmCollectionFromResults($results);
    }

    public function getFilmsInTheatres(int $numResults, string $sortBy): FilmCollection
    {
        /** @var GetFilmsInTheatres $query */
        $query = $this->getQuery(GetFilmsInTheatres::class);
        $results = $query->getResult($numResults, $sortBy);

        return $this->populateFilmCollectionFromResults($results);
    }

    private function populateFilmCollectionFromResults(array $results): FilmCollection
    {
        if (!$results) {
            return new FilmCollection();
        }

        $films = [];

        foreach ($results as $result) {
            $films[] = Film::buildFromArray($result);
        }

        return new FilmCollection(...$films);
    }
}
