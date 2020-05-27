<?php

namespace App\Domain\Entity;

class FilmAttribute
{
    public const FIELD_NAME = 'name';

    private ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_NAME => $this->getName(),
        ];
    }
}
