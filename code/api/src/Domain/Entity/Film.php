<?php

namespace App\Domain\Entity;

class Film
{
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
    private ?string $directors;
    private ?string $actors;
    private ?string $synopsis;
    private ?string $topics;
    private ?string $screenplayers;
    private ?string $musicians;
    private ?string $cinematographers;

    public function getIdFilm(): int
    {
        return $this->idFilm;
    }

    public function setIdFilm(int $idFilm): void
    {
        $this->idFilm = $idFilm;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getOriginalTitle(): string
    {
        return $this->originalTitle;
    }

    public function setOriginalTitle(string $originalTitle): void
    {
        $this->originalTitle = $originalTitle;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): void
    {
        $this->rating = $rating;
    }

    public function getNumRatings(): ?int
    {
        return $this->numRatings;
    }

    public function setNumRatings(?int $numRatings): void
    {
        $this->numRatings = $numRatings;
    }

    public function getPopularityRanking(): ?int
    {
        return $this->popularityRanking;
    }

    public function setPopularityRanking(?int $popularityRanking): void
    {
        $this->popularityRanking = $popularityRanking;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function isInTheatres(): bool
    {
        return $this->inTheatres;
    }

    public function setInTheatres(bool $inTheatres): void
    {
        $this->inTheatres = $inTheatres;
    }

    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?string $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getDirectors(): ?string
    {
        return $this->directors;
    }

    public function setDirectors(?string $directors): void
    {
        $this->directors = $directors;
    }

    public function getActors(): ?string
    {
        return $this->actors;
    }

    public function setActors(?string $actors): void
    {
        $this->actors = $actors;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): void
    {
        $this->synopsis = $synopsis;
    }

    public function getTopics(): ?string
    {
        return $this->topics;
    }

    public function setTopics(?string $topics): void
    {
        $this->topics = $topics;
    }

    public function getScreenplayers(): ?string
    {
        return $this->screenplayers;
    }

    public function setScreenplayers(?string $screenplayers): void
    {
        $this->screenplayers = $screenplayers;
    }

    public function getMusicians(): ?string
    {
        return $this->musicians;
    }

    public function setMusicians(?string $musicians): void
    {
        $this->musicians = $musicians;
    }

    public function getCinematographers(): ?string
    {
        return $this->cinematographers;
    }

    public function setCinematographers(?string $cinematographers): void
    {
        $this->cinematographers = $cinematographers;
    }
}