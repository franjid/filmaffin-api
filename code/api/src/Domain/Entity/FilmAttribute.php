<?php

namespace App\Domain\Entity;

class FilmAttribute
{
    final public const FIELD_NAME = 'name';

    public function __construct(private readonly string $name)
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function toArray(): string
    {
        return $this->getName();
    }

    public static function buildFromArray(array $data): self
    {
        return new self($data[self::FIELD_NAME]);
    }
}
