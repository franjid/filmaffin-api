<?php

namespace Entity;

class FilmExtraInfo
{
    private int $idFilm;
    private ?string $directors;
    private ?string $actors;
    private ?string $screenplayers;
    private ?string $musicians;
    private ?string $cinematographers;
    private ?string $topics;

    public function getIdFilm(): int
    {
        return $this->idFilm;
    }

    public function setIdFilm(int $idFilm): void
    {
        $this->idFilm = $idFilm;
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

    public function getTopics(): ?string
    {
        return $this->topics;
    }

    public function setTopics(?string $topics): void
    {
        $this->topics = $topics;
    }
}
