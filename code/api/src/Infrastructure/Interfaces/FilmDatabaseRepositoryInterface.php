<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\Collection\FilmAttributeCollection;
use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\Collection\FilmRatedByUserCollection;
use App\Domain\Entity\Collection\PlatformCollection;
use App\Domain\Entity\Collection\UserReviewCollection;

interface FilmDatabaseRepositoryInterface
{
    public function getFilmsCount(?int $dateUpdatedNewestThanTimestamp): int;

    public function getFilms(int $offset, int $limit, ?int $dateUpdatedNewestThanTimestamp): FilmCollection;

    public function getFilmsById(array $idFilms): FilmCollection;

    public function getFrequentlyUpdatedFilms(): FilmCollection;

    public function getFilmDirectors(int $idFilm): FilmParticipantCollection;

    public function getFilmActors(int $idFilm): FilmParticipantCollection;

    public function getFilmScreenplayers(int $idFilm): FilmParticipantCollection;

    public function getFilmMusicians(int $idFilm): FilmParticipantCollection;

    public function getFilmCinematographers(int $idFilm): FilmParticipantCollection;

    public function getFilmGenres(int $idFilm): FilmAttributeCollection;

    public function getFilmTopics(int $idFilm): FilmAttributeCollection;

    public function getUserReviews(int $idFilm): UserReviewCollection;

    public function getPlatforms(int $idFilm): PlatformCollection;

    public function getFilmsRatedByUserFriends(
        int $idUser,
        int $numResults,
        int $offset
    ): FilmRatedByUserCollection;
}
