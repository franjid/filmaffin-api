<?php

namespace App\Domain\Entity;

class ProReview
{
    final public const FIELD_AUTHOR = 'author';
    final public const FIELD_REVIEW = 'review';
    final public const FIELD_TREND = 'trend';

    public function __construct(private readonly string $author, private readonly string $review, private readonly string $trend)
    {
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getReview(): string
    {
        return $this->review;
    }

    public function getTrend(): string
    {
        return $this->trend;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_AUTHOR => $this->getAuthor(),
            self::FIELD_REVIEW => $this->getReview(),
            self::FIELD_TREND => $this->getTrend(),
        ];
    }

    public static function buildFromArray(array $data): self
    {
        return new self(
            $data[self::FIELD_AUTHOR],
            $data[self::FIELD_REVIEW],
            $data[self::FIELD_TREND]
        );
    }
}
