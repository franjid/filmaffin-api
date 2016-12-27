<?php

namespace Repository\Index\Film;

use Query\Index\Film\GetFilm;
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
}
