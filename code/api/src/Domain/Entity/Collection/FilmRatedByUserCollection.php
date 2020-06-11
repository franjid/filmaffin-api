<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\FilmRatedByUser;

class FilmRatedByUserCollection
{
    /** @var FilmRatedByUser[] $filmsRatedByUser */
    private array $filmsRatedByUser;

    public function __construct(?FilmRatedByUser ...$filmsRatedByUsers)
    {
        $this->filmsRatedByUser = $filmsRatedByUsers;
    }

    public function getItems(): array
    {
        return $this->filmsRatedByUser;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->filmsRatedByUser as $attribute) {
            $result[] = $attribute->toArray();
        }

        return $result;
    }
}
