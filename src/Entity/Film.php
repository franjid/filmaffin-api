<?php

namespace Entity;

class Film
{
    /** @var int $idFilm */
    protected $idFilm;

    /** @var string $title */
    protected $title;

    /** @var string $originalTitle */
    protected $originalTitle;

    /** @var float $rating */
    protected $rating;

    /** @var int $numRatings */
    protected $numRatings;

    /** @var int $numRatings */
    protected $popularityRanking;

    /** @var int $year */
    protected $year;

    /** @var int $duration */
    protected $duration;

    /** @var string $country */
    protected $country;

    /** @var string $directors */
    protected $directors;

    /** @var string $actors */
    protected $actors;


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
     * @return string
     */
    public function getOriginalTitle()
    {
        return $this->originalTitle;
    }

    /**
     * @param string $originalTitle
     */
    public function setOriginalTitle($originalTitle)
    {
        $this->originalTitle = $originalTitle;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
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

    /**
     * @return int
     */
    public function getPopularityRanking()
    {
        return $this->popularityRanking;
    }

    /**
     * @param int $popularityRanking
     */
    public function setPopularityRanking($popularityRanking)
    {
        $this->popularityRanking = $popularityRanking;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
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
}
