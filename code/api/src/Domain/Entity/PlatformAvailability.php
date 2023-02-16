<?php

namespace App\Domain\Entity;

class PlatformAvailability
{
    public const FIELD_TYPE = 'type';
    public const FIELD_NAME = 'name';

    private string $type;
    private string $name;

    public function __construct(
        string $type,
        string $name
    )
    {
        $this->type = $type;
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_TYPE => $this->getType(),
            self::FIELD_NAME => $this->getName(),
        ];
    }

    public static function buildFromArray(array $data): self
    {
        return new self(
            $data[self::FIELD_TYPE],
            $data[self::FIELD_NAME],
        );
    }
}
