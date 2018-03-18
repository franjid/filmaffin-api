<?php

namespace Repository\Db\Film;

use Query\Db\Film\GetFilmExtraInfo;
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

    /**
     * {@inheritdoc}
     */
    public function getFilmExtraInfo(array $idFilms)
    {
        /** @var GetFilmExtraInfo $query */
        $query = $this->getQuery(GetFilmExtraInfo::DIC_NAME);

        return $query->getResult($idFilms);
    }
}
