<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\FilmRatedByUserExtended;

class FilmRatedByUserExtendedCollection
{
    /** @var FilmRatedByUserExtended[] $filmsRatedByUserExtended */
    private array $filmsRatedByUserExtended;

    public function __construct(?FilmRatedByUserExtended ...$filmsRatedByUsersExtended)
    {
        $this->filmsRatedByUserExtended = $filmsRatedByUsersExtended;
    }

    public function getItems(): array
    {
        return $this->filmsRatedByUserExtended;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->filmsRatedByUserExtended as $attribute) {
            $result[] = $attribute->toArray();
        }

        return $result;
    }
}
