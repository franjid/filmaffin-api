<?php

namespace Repository\Index\Film;

interface FilmRepositoryInterface
{
    const DIC_NAME = 'Repository.Index.Film.FilmRepositoryInterface';

    /**
     * @param string $title
     * @return [][]
     */
    public function searchFilms($title);

    /**
     * @param int $idFilm
     * @return array
     */
    public function getFilm($idFilm);

    /**
     * @return array
     */
    public function getPopularFilms();

    /**
     * @return array
     */
    public function getFilmsInTheatres();
}
