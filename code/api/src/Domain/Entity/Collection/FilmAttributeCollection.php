<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\FilmAttribute;

class FilmAttributeCollection
{
    /** @var FilmAttribute[] */
    private readonly array $attributes;

    public function __construct(?FilmAttribute ...$attributes)
    {
        $this->attributes = $attributes;
    }

    public function getItems(): array
    {
        return $this->attributes;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->attributes as $attribute) {
            $result[] = $attribute->toArray();
        }

        return $result;
    }
}
