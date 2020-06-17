<?php

namespace App\Domain\Entity;

class ProReview
{
    public const FIELD_AUTHOR = 'author';
    public const FIELD_REVIEW = 'review';
    public const FIELD_TREND = 'trend';

    private string $author;
    private string $review;
    private string $trend;

    public function __construct(
        string $author,
        string $review,
        string $trend
    )
    {
        $this->author = $author;
        $this->review = $review;
        $this->trend = $trend;
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
