<?php

namespace Repository\Index\Film;

use Query\Index\Film\GetFilm;
use Query\Index\Film\GetFilmsInTheatres;
use Query\Index\Film\GetPopularFilms;
use Query\Index\Film\SearchFilms;
use Repository\RepositoryAbstract;

class FilmRepository extends RepositoryAbstract implements FilmRepositoryInterface
{
    public function searchFilms(string $title): array
    {
        /** @var SearchFilms $query */
        $query = $this->getQuery(SearchFilms::DIC_NAME);

        return $query->getResult($title);
    }

    public function getFilm(int $idFilm): array
    {
        /** @var GetFilm $query */
        $query = $this->getQuery(GetFilm::DIC_NAME);

        return $query->getResult($idFilm);
    }

    public function getPopularFilms(int $numResults, int $offset): array
    {
        /** @var GetPopularFilms $query */
        $query = $this->getQuery(GetPopularFilms::DIC_NAME);

        return $query->getResult($numResults, $offset);
    }

    public function getFilmsInTheatres(string $sortBy): array
    {
        /** @var GetFilmsInTheatres $query */
        $query = $this->getQuery(GetFilmsInTheatres::DIC_NAME);

        return $query->getResult($sortBy);
    }
}
