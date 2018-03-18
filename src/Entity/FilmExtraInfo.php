<?php

namespace Entity;

class FilmExtraInfo
{
    /** @var int $idFilm */
    protected $idFilm;

    /** @var string $directors */
    protected $directors;

    /** @var string $actors */
    protected $actors;

    /** @var string $screenplayers */
    protected $screenplayers;

    /** @var string $musicians */
    protected $musicians;

    /** @var string $cinematographers */
    protected $cinematographers;

    /** @var string $topics */
    protected $topics;

    /**
     * @return int
     */
    public function getIdFilm()
    {
        return $this->idFilm;
    }

    /**
     * @param int $idFilm
     */
    public function setIdFilm($idFilm)
    {
        $this->idFilm = $idFilm;
    }

    /**
     * @return string
     */
    public function getDirectors()
    {
        return $this->directors;
    }

    /**
     * @param string $directors
     */
    public function setDirectors($directors)
    {
        $this->directors = $directors;
    }

    /**
     * @return string
     */
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * @param string $actors
     */
    public function setActors($actors)
    {
        $this->actors = $actors;
    }

    /**
     * @return string
     */
    public function getScreenplayers()
    {
        return $this->screenplayers;
    }

    /**
     * @param string $screenplayers
     */
    public function setScreenplayers($screenplayers)
    {
        $this->screenplayers = $screenplayers;
    }

    /**
     * @return string
     */
    public function getMusicians()
    {
        return $this->musicians;
    }

    /**
     * @param string $musicians
     */
    public function setMusicians($musicians)
    {
        $this->musicians = $musicians;
    }

    /**
     * @return string
     */
    public function getCinematographers()
    {
        return $this->cinematographers;
    }

    /**
     * @param string $cinematographers
     */
    public function setCinematographers($cinematographers)
    {
        $this->cinematographers = $cinematographers;
    }

    /**
     * @return string
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * @param string $topics
     */
    public function setTopics($topics)
    {
        $this->topics = $topics;
    }
}
