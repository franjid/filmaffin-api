<?php

namespace Repository\Film;

use Entity\Film;

interface FilmRepositoryInterface
{
    const DIC_NAME = 'Repository.Film.FilmRepositoryInterface';

    /**
     * @param int $offset
     * @param int $limit
     * @return Film[]
     */
    public function getFilms($offset, $limit);
}
