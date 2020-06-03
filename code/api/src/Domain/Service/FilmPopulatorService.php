<?php

namespace App\Domain\Service;

use App\Domain\Entity\Film;
use App\Domain\Entity\FilmAttribute;
use App\Domain\Entity\FilmParticipant;
use App\Domain\Interfaces\FilmPopulatorInterface;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;

class FilmPopulatorService implements FilmPopulatorInterface
{
    private FilmDatabaseRepositoryInterface $filmDatabaseRepository;

    public function __construct(
        FilmDatabaseRepositoryInterface $filmDatabaseRepository
    )
    {
        $this->filmDatabaseRepository = $filmDatabaseRepository;
    }

    public function populateFilm(Film $film): Film
    {
        $idFilm = $film->getIdFilm();

        $directors = $this->filmDatabaseRepository->getFilmDirectors($idFilm)->toArray();
        $film->setDirectors(implode(', ', array_column($directors, FilmParticipant::FIELD_NAME)));

        $actors = $this->filmDatabaseRepository->getFilmActors($idFilm)->toArray();
        $film->setActors(implode(', ', array_column($actors, FilmParticipant::FIELD_NAME)));

        $screenplayers = $this->filmDatabaseRepository->getFilmScreenplayers($idFilm)->toArray();
        $film->setScreenplayers(implode(', ', array_column($screenplayers, FilmParticipant::FIELD_NAME)));

        $musicians = $this->filmDatabaseRepository->getFilmMusicians($idFilm)->toArray();
        $film->setMusicians(implode(', ', array_column($musicians, FilmParticipant::FIELD_NAME)));

        $cinematographers = $this->filmDatabaseRepository->getFilmCinematographers($idFilm)->toArray();
        $film->setCinematographers(implode(', ', array_column($cinematographers, FilmParticipant::FIELD_NAME)));

        $topics = $this->filmDatabaseRepository->getFilmTopics($idFilm)->toArray();
        $film->setTopics(implode(', ', array_column($topics, FilmAttribute::FIELD_NAME)));

        return $film;
    }
}
