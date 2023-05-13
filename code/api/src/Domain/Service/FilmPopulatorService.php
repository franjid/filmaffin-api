<?php

namespace App\Domain\Service;

use App\Domain\Entity\Film;
use App\Domain\Interfaces\FilmPopulatorInterface;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;

class FilmPopulatorService implements FilmPopulatorInterface
{
    public function __construct(private readonly FilmDatabaseRepositoryInterface $filmDatabaseRepository)
    {
    }

    public function populateFilm(Film $film): Film
    {
        $idFilm = $film->getIdFilm();

        $film->setDirectors($this->filmDatabaseRepository->getFilmDirectors($idFilm));
        $film->setActors($this->filmDatabaseRepository->getFilmActors($idFilm));
        $film->setScreenplayers($this->filmDatabaseRepository->getFilmScreenplayers($idFilm));
        $film->setMusicians($this->filmDatabaseRepository->getFilmMusicians($idFilm));
        $film->setCinematographers($this->filmDatabaseRepository->getFilmCinematographers($idFilm));
        $film->setGenres($this->filmDatabaseRepository->getFilmGenres($idFilm));
        $film->setTopics($this->filmDatabaseRepository->getFilmTopics($idFilm));
        $film->setUserReviews($this->filmDatabaseRepository->getUserReviews($idFilm));
        $film->setPlatforms($this->filmDatabaseRepository->getPlatforms($idFilm));

        return $film;
    }
}
