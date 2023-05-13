<?php

namespace App\Domain\Entity;

class PlatformAvailability
{
    final public const FIELD_TYPE = 'type';
    final public const FIELD_NAME = 'name';

    public function __construct(private readonly string $type, private readonly string $name)
    {
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
