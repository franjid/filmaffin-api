<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\Collection\FilmAttributeCollection;
use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\Collection\FilmRatedByUserCollection;

interface FilmDatabaseRepositoryInterface
{
    public function getFilms(int $offset, int $limit): FilmCollection;
    public function getFrequentlyUpdatedFilms(): FilmCollection;
    public function getFilmDirectors(int $idFilm): FilmParticipantCollection;
    public function getFilmActors(int $idFilm): FilmParticipantCollection;
    public function getFilmScreenplayers(int $idFilm): FilmParticipantCollection;
    public function getFilmMusicians(int $idFilm): FilmParticipantCollection;
    public function getFilmCinematographers(int $idFilm): FilmParticipantCollection;
    public function getFilmTopics(int $idFilm): FilmAttributeCollection;
    public function getFilmsRatedByUserFriends(
        int $idUser,
        int $numResults,
        int $offset
    ): FilmRatedByUserCollection;
}
