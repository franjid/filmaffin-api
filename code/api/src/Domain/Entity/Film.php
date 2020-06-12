<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Collection\FilmAttributeCollection;
use App\Domain\Entity\Collection\FilmParticipantCollection;

class Film
{
    public const FIELD_ID_FILM = 'idFilm';
    public const FIELD_TITLE = 'title';
    public const FIELD_ORIGINAL_TITLE = 'originalTitle';
    public const FIELD_RATING = 'rating';
    public const FIELD_NUM_RATINGS = 'numRatings';
    public const FIELD_POPULARITY_RANKING = 'popularityRanking';
    public const FIELD_YEAR = 'year';
    public const FIELD_DURATION = 'duration';
    public const FIELD_COUNTRY = 'country';
    public const FIELD_IN_THEATRES = 'inTheatres';
    public const FIELD_RELEASE_DATE = 'releaseDate';
    public const FIELD_SYNOPSIS = 'synopsis';
    public const FIELD_DIRECTORS = 'directors';
    public const FIELD_ACTORS = 'actors';
    public const FIELD_SCREENPLAYERS = 'screenplayers';
    public const FIELD_MUSICIANS = 'musicians';
    public const FIELD_CINEMATOGRAPHERS = 'cinematographers';
    public const FIELD_TOPICS = 'topics';
    public const FIELD_POSTER_IMAGES = 'posterImages';

    private int $idFilm;
    private string $title;
    private string $originalTitle;
    private ?float $rating;
    private ?int $numRatings;
    private ?int $popularityRanking;
    private int $year;
    private ?int $duration;
    private string $country;
    private bool $inTheatres;
    private ?string $releaseDate;
    private ?string $synopsis;
    private FilmParticipantCollection $directors;
    private FilmParticipantCollection $actors;
    private FilmParticipantCollection $screenplayers;
    private FilmParticipantCollection $musicians;
    private FilmParticipantCollection $cinematographers;
    private FilmAttributeCollection $topics;
    private ?PosterImages $posterImages;

    public function __construct(
        int $idFilm,
        string $title,
        string $originalTitle,
        ?float $rating,
        ?int $numRatings,
        ?int $popularityRanking,
        int $year,
        ?int $duration,
        string $country,
        bool $inTheatres,
        ?string $releaseDate,
        ?string $synopsis,
        FilmParticipantCollection $directors,
        FilmParticipantCollection $actors,
        FilmParticipantCollection $screenplayers,
        FilmParticipantCollection $musicians,
        FilmParticipantCollection $cinematographers,
        FilmAttributeCollection $topics,
        ?PosterImages $posterImages
    )
    {
        $this->idFilm = $idFilm;
        $this->title = $title;
        $this->originalTitle = $originalTitle;
        $this->rating = $rating;
        $this->numRatings = $numRatings;
        $this->popularityRanking = $popularityRanking;
        $this->year = $year;
        $this->duration = $duration;
        $this->country = $country;
        $this->inTheatres = $inTheatres;
        $this->releaseDate = $releaseDate;
        $this->synopsis = $synopsis;
        $this->directors = $directors;
        $this->actors = $actors;
        $this->screenplayers = $screenplayers;
        $this->musicians = $musicians;
        $this->cinematographers = $cinematographers;
        $this->topics = $topics;
        $this->posterImages = $posterImages;
    }

    public function getIdFilm(): int
    {
        return $this->idFilm;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOriginalTitle(): string
    {
        return $this->originalTitle;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function getNumRatings(): ?int
    {
        return $this->numRatings;
    }

    public function getPopularityRanking(): ?int
    {
        return $this->popularityRanking;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function isInTheatres(): bool
    {
        return $this->inTheatres;
    }

    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function getDirectors(): FilmParticipantCollection
    {
        return $this->directors;
    }

    public function setDirectors(FilmParticipantCollection $directors): void
    {
        $this->directors = $directors;
    }

    public function getActors(): FilmParticipantCollection
    {
        return $this->actors;
    }

    public function setActors(FilmParticipantCollection $actors): void
    {
        $this->actors = $actors;
    }

    public function getScreenplayers(): FilmParticipantCollection
    {
        return $this->screenplayers;
    }

    public function setScreenplayers(FilmParticipantCollection $screenplayers): void
    {
        $this->screenplayers = $screenplayers;
    }

    public function getMusicians(): FilmParticipantCollection
    {
        return $this->musicians;
    }

    public function setMusicians(FilmParticipantCollection $musicians): void
    {
        $this->musicians = $musicians;
    }

    public function getCinematographers(): FilmParticipantCollection
    {
        return $this->cinematographers;
    }

    public function setCinematographers(FilmParticipantCollection $cinematographers): void
    {
        $this->cinematographers = $cinematographers;
    }

    public function getTopics(): FilmAttributeCollection
    {
        return $this->topics;
    }

    public function setTopics(FilmAttributeCollection $topics): void
    {
        $this->topics = $topics;
    }

    public function getPosterImages(): ?PosterImages
    {
        return $this->posterImages;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_ID_FILM => $this->getIdFilm(),
            self::FIELD_TITLE => $this->getTitle(),
            self::FIELD_ORIGINAL_TITLE => $this->getOriginalTitle(),
            self::FIELD_RATING => $this->getRating(),
            self::FIELD_NUM_RATINGS => $this->getNumRatings(),
            self::FIELD_POPULARITY_RANKING => $this->getPopularityRanking(),
            self::FIELD_YEAR => $this->getYear(),
            self::FIELD_DURATION => $this->getDuration(),
            self::FIELD_COUNTRY => $this->getCountry(),
            self::FIELD_IN_THEATRES => $this->isInTheatres(),
            self::FIELD_RELEASE_DATE => $this->getReleaseDate(),
            self::FIELD_SYNOPSIS => $this->getSynopsis(),
            self::FIELD_DIRECTORS => $this->getDirectors()->toArray(),
            self::FIELD_ACTORS => $this->getActors()->toArray(),
            self::FIELD_SCREENPLAYERS => $this->getScreenplayers()->toArray(),
            self::FIELD_MUSICIANS => $this->getMusicians()->toArray(),
            self::FIELD_CINEMATOGRAPHERS => $this->getCinematographers()->toArray(),
            self::FIELD_TOPICS => $this->getTopics()->toArray(),
            self::FIELD_POSTER_IMAGES => $this->getPosterImages() ? $this->getPosterImages()->toArray() : null,
        ];
    }

    public static function buildFromArray(array $data): self
    {
        $directors = isset($data[self::FIELD_DIRECTORS]) && is_array($data[self::FIELD_DIRECTORS])
            ? new FilmParticipantCollection(
                ...array_map(static function($name) {return new FilmParticipant($name);}, $data[self::FIELD_DIRECTORS])
            )
            : new FilmParticipantCollection();

        $actors = isset($data[self::FIELD_ACTORS]) && is_array($data[self::FIELD_ACTORS])
            ? new FilmParticipantCollection(
                ...array_map(static function($name) {return new FilmParticipant($name);}, $data[self::FIELD_ACTORS])
            )
            : new FilmParticipantCollection();

        $screenplayers = isset($data[self::FIELD_SCREENPLAYERS]) && is_array($data[self::FIELD_SCREENPLAYERS])
            ? new FilmParticipantCollection(
                ...array_map(static function($name) {return new FilmParticipant($name);}, $data[self::FIELD_SCREENPLAYERS])
            )
            : new FilmParticipantCollection();

        $musicians = isset($data[self::FIELD_MUSICIANS]) && is_array($data[self::FIELD_MUSICIANS])
            ? new FilmParticipantCollection(
                ...array_map(static function($name) {return new FilmParticipant($name);}, $data[self::FIELD_MUSICIANS])
            )
            : new FilmParticipantCollection();

        $cinematographers = isset($data[self::FIELD_CINEMATOGRAPHERS]) && is_array($data[self::FIELD_CINEMATOGRAPHERS])
            ? new FilmParticipantCollection(
                ...array_map(static function($name) {return new FilmParticipant($name);}, $data[self::FIELD_CINEMATOGRAPHERS])
            )
            : new FilmParticipantCollection();

        $topics = isset($data[self::FIELD_TOPICS]) && is_array($data[self::FIELD_TOPICS])
            ? new FilmAttributeCollection(
                ...array_map(static function($name) {return new FilmAttribute($name);}, $data[self::FIELD_TOPICS])
            )
            : new FilmAttributeCollection();

        return new self(
            $data[self::FIELD_ID_FILM],
            $data[self::FIELD_TITLE],
            $data[self::FIELD_ORIGINAL_TITLE],
            $data[self::FIELD_RATING] ?? null,
            $data[self::FIELD_NUM_RATINGS] ?? null,
            $data[self::FIELD_POPULARITY_RANKING] ?? null,
            $data[self::FIELD_YEAR] ?? null,
            $data[self::FIELD_DURATION] ?? null,
            $data[self::FIELD_COUNTRY],
            isset($data[self::FIELD_IN_THEATRES]) ? (bool) $data[self::FIELD_IN_THEATRES] : false,
            $data[self::FIELD_RELEASE_DATE] ?? null,
            $data[self::FIELD_SYNOPSIS] ?? null,
            $directors,
            $actors,
            $screenplayers,
            $musicians,
            $cinematographers,
            $topics,
            $data[self::FIELD_POSTER_IMAGES] ? PosterImages::buildFromArray($data[self::FIELD_POSTER_IMAGES]) : null,
        );
    }
}
