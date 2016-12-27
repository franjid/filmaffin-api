<?php

namespace Repository\Db\Film;

use Query\Db\Film\GetFilms;
use Repository\RepositoryAbstract;

class FilmRepository extends RepositoryAbstract implements FilmRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFilms($offset, $limit)
    {
        /** @var GetFilms $query */
        $query = $this->getQuery(GetFilms::DIC_NAME);

        return $query->getResult($offset, $limit);
    }
}