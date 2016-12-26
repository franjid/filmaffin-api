<?php

namespace Repository\Index\Film;

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
}
