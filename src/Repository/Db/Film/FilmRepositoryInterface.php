<?php

namespace Repository\Db\Film;

use Entity\Film;
use Entity\FilmExtraInfo;

interface FilmRepositoryInterface
{
    public const DIC_NAME = 'Repository.Db.Film.FilmRepositoryInterface';

    /**
     * @param int $offset
     * @param int $limit
     * @return Film[]
     */
    public function getFilms(int $offset, int $limit): array;

    /**
     * @param array $idFilms
     * @return FilmExtraInfo[]
     */
    public function getFilmExtraInfo(array $idFilms): array;
}
