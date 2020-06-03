<?php

namespace App\Domain\Interfaces;

use App\Domain\Entity\Film;

interface FilmPopulatorInterface
{
    public function populateFilm(Film $film): Film;
}
