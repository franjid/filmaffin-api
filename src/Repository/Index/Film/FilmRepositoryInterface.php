<?php

namespace Repository\Index\Film;

interface FilmRepositoryInterface
{
    const DIC_NAME = 'Repository.Index.Film.FilmRepositoryInterface';

    /**
     * @return array
     */
    public function searchFilms($title);
}
