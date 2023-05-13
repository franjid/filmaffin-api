<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\Film;

class FilmCollection
{
    /** @var Film[] */
    private array $films;

    public function __construct(Film ...$films)
    {
        $this->films = $films;
    }

    public function getItems(): array
    {
        return $this->films;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->films as $film) {
            $result[] = $film->toArray();
        }

        return $result;
    }
}
