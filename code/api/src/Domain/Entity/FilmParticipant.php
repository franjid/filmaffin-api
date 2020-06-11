<?php

namespace App\Domain\Entity;

class FilmParticipant
{
    public const FIELD_NAME = 'name';

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_NAME => $this->getName(),
        ];
    }

    public static function buildFromArray(array $data): self
    {
        return new self($data[self::FIELD_NAME]);
    }
}
