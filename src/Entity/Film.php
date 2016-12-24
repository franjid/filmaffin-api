<?php

namespace Entity;

class Film
{
    /** @var int $idFilm */
    protected $idFilm;

    /** @var string $title */
    protected $title;

    /** @var int $numRatings */
    protected $numRatings;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getNumRatings()
    {
        return $this->numRatings;
    }

    /**
     * @param int $numRatings
     */
    public function setNumRatings($numRatings)
    {
        $this->numRatings = $numRatings;
    }
}
