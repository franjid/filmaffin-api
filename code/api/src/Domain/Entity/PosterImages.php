<?php

namespace App\Domain\Entity;

class PosterImages
{
    final public const FIELD_SMALL = 'small';
    final public const FIELD_MEDIUM = 'medium';
    final public const FIELD_LARGE = 'large';

    public function __construct(private readonly string $small, private readonly string $medium, private readonly string $large)
    {
    }

    public function getSmall(): string
    {
        return $this->small;
    }

    public function getMedium(): string
    {
        return $this->medium;
    }

    public function getLarge(): string
    {
        return $this->large;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_SMALL => $this->getSmall(),
            self::FIELD_MEDIUM => $this->getMedium(),
            self::FIELD_LARGE => $this->getLarge(),
        ];
    }

    public static function buildFromArray(array $data): self
    {
        return new self(
            $data[self::FIELD_SMALL],
            $data[self::FIELD_MEDIUM],
            $data[self::FIELD_LARGE]
        );
    }
}
