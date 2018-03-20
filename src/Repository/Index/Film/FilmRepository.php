<?php

namespace Repository\Index\Film;

use Query\Index\Film\GetFilm;
use Query\Index\Film\GetFilmsInTheatres;
use Query\Index\Film\GetPopularFilms;
use Query\Index\Film\SearchFilms;
use Repository\RepositoryAbstract;

class FilmRepository extends RepositoryAbstract implements FilmRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function searchFilms($title)
    {
        /** @var SearchFilms $query */
        $query = $this->getQuery(SearchFilms::DIC_NAME);

        return $query->getResult($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilm($idFilm)
    {
        /** @var GetFilm $query */
        $query = $this->getQuery(GetFilm::DIC_NAME);

        return $query->getResult($idFilm);
    }

    /**
     * {@inheritdoc}
     */
    public function getPopularFilms($numResults, $offset)
    {
        /** @var GetPopularFilms $query */
        $query = $this->getQuery(GetPopularFilms::DIC_NAME);

        return $query->getResult($numResults, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilmsInTheatres($sortBy)
    {
        /** @var GetFilmsInTheatres $query */
        $query = $this->getQuery(GetFilmsInTheatres::DIC_NAME);

        return $query->getResult($sortBy);
    }
}
