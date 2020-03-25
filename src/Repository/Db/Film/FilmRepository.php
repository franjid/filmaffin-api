<?php

namespace Repository\Db\Film;

use Query\Db\Film\GetFilmExtraInfo;
use Query\Db\Film\GetFilms;
use Repository\RepositoryAbstract;

class FilmRepository extends RepositoryAbstract implements FilmRepositoryInterface
{
    public function getFilms(int $offset, int $limit): array
    {
        /** @var GetFilms $query */
        $query = $this->getQuery(GetFilms::DIC_NAME);

        return $query->getResult($offset, $limit);
    }

    public function getFilmExtraInfo(array $idFilms): array
    {
        /** @var GetFilmExtraInfo $query */
        $query = $this->getQuery(GetFilmExtraInfo::DIC_NAME);

        return $query->getResult($idFilms);
    }
}
