<?php

namespace App\Infrastructure\Repository\Database;

use App\Domain\Entity\Collection\FilmAttributeCollection;
use App\Domain\Entity\Collection\FilmCollection;
use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\Collection\FilmRatedByUserCollection;
use App\Domain\Entity\Film;
use App\Domain\Entity\FilmAttribute;
use App\Domain\Entity\FilmParticipant;
use App\Domain\Entity\FilmRatedByUser;
use App\Domain\Entity\UserFilmaffinity;
use App\Infrastructure\Interfaces\FilmDatabaseRepositoryInterface;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmActors;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmCinematographers;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmDirectors;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmMusicians;
use App\Infrastructure\Repository\Database\Query\Film\GetFilms;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmScreenplayers;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmsRatedByUserFriends;
use App\Infrastructure\Repository\Database\Query\Film\GetFilmTopics;
use App\Infrastructure\Repository\Database\Query\Film\GetFrequentlyUpdatedFilms;
use App\Infrastructure\Repository\RepositoryAbstract;

class FilmDatabaseMysqlRepository extends RepositoryAbstract implements FilmDatabaseRepositoryInterface
{
    public function getFilms(int $offset, int $limit): FilmCollection
    {
        /** @var GetFilms $query */
        $query = $this->getQuery(GetFilms::class);
        $results = $query->getResult($offset, $limit);

        return $this->populateFilmCollectionFromResults($results);
    }

    public function getFrequentlyUpdatedFilms(): FilmCollection
    {
        /** @var GetFrequentlyUpdatedFilms $query */
        $query = $this->getQuery(GetFrequentlyUpdatedFilms::class);
        $results = $query->getResult();

        return $this->populateFilmCollectionFromResults($results);
    }

    private function populateFilmCollectionFromResults(array $results): FilmCollection
    {
        if (!$results) {
            return new FilmCollection();
        }

        $films = [];

        foreach ($results as $result) {
            $films[] = Film::buildFromArray($result);
        }

        return new FilmCollection(...$films);
    }

    public function getFilmDirectors(int $idFilm): FilmParticipantCollection
    {
        /** @var GetFilmDirectors $query */
        $query = $this->getQuery(GetFilmDirectors::class);
        $results = $query->getResult($idFilm);

        return $this->populateFilmParticipantCollectionFromResults($results);
    }

    public function getFilmActors(int $idFilm): FilmParticipantCollection
    {
        /** @var GetFilmActors $query */
        $query = $this->getQuery(GetFilmActors::class);
        $results = $query->getResult($idFilm);

        return $this->populateFilmParticipantCollectionFromResults($results);
    }

    public function getFilmScreenplayers(int $idFilm): FilmParticipantCollection
    {
        /** @var GetFilmScreenplayers $query */
        $query = $this->getQuery(GetFilmScreenplayers::class);
        $results = $query->getResult($idFilm);

        return $this->populateFilmParticipantCollectionFromResults($results);
    }

    public function getFilmMusicians(int $idFilm): FilmParticipantCollection
    {
        /** @var GetFilmMusicians $query */
        $query = $this->getQuery(GetFilmMusicians::class);
        $results = $query->getResult($idFilm);

        return $this->populateFilmParticipantCollectionFromResults($results);
    }

    public function getFilmCinematographers(int $idFilm): FilmParticipantCollection
    {
        /** @var GetFilmCinematographers $query */
        $query = $this->getQuery(GetFilmCinematographers::class);
        $results = $query->getResult($idFilm);

        return $this->populateFilmParticipantCollectionFromResults($results);
    }

    private function populateFilmParticipantCollectionFromResults(array $results): FilmParticipantCollection
    {
        if (!$results) {
            return new FilmParticipantCollection();
        }

        $participants = [];

        foreach ($results as $result) {
            $participants[] = FilmParticipant::buildFromArray($result);
        }

        return new FilmParticipantCollection(...$participants);
    }

    public function getFilmTopics(int $idFilm): FilmAttributeCollection
    {
        /** @var GetFilmTopics $query */
        $query = $this->getQuery(GetFilmTopics::class);
        $results = $query->getResult($idFilm);

        return $this->populateFilmAttributeCollectionFromResults($results);
    }

    private function populateFilmAttributeCollectionFromResults(array $results): FilmAttributeCollection
    {
        if (!$results) {
            return new FilmAttributeCollection();
        }

        $attributes = [];

        foreach ($results as $result) {
            $attributes[] = FilmAttribute::buildFromArray($result);
        }

        return new FilmAttributeCollection(...$attributes);
    }

    public function getFilmsRatedByUserFriends(
        int $idUser,
        int $numResults,
        int $offset
    ): FilmRatedByUserCollection
    {
        /** @var GetFilmsRatedByUserFriends $query */
        $query = $this->getQuery(GetFilmsRatedByUserFriends::class);
        $results = $query->getResult($idUser, $numResults, $offset);

        if (!$results) {
            return new FilmRatedByUserCollection();
        }

        $filmsRatedByUser = [];

        foreach ($results as $result) {
            $filmsRatedByUser[] = new FilmRatedByUser(
                $result['idFilm'],
                new UserFilmaffinity(
                    $result['idUser'],
                    $result['name'],
                    null
                ),
                $result['rating'],
                (new \DateTimeImmutable())->setTimestamp($result['dateRated'])
            );
        }

        return new FilmRatedByUserCollection(...$filmsRatedByUser);
    }
}
