<?php

namespace App\Domain\Entity;

class UserReview
{
    final public const FIELD_USERNAME = 'username';
    final public const FIELD_ID_USER = 'idUser';
    final public const FIELD_RATING = 'rating';
    final public const FIELD_TITLE = 'title';
    final public const FIELD_REVIEW = 'review';
    final public const FIELD_SPOILER = 'spoiler';
    final public const FIELD_DATE_PUBLISHED = 'datePublished';

    public function __construct(
        private readonly string $username,
        private readonly int $idUser,
        private readonly ?int $rating,
        private readonly string $title,
        private readonly string $review,
        private readonly ?string $spoiler,
        private readonly \DateTimeImmutable $datePublished
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getReview(): string
    {
        return $this->review;
    }

    public function getSpoiler(): ?string
    {
        return $this->spoiler;
    }

    public function getDatePublished(): \DateTimeImmutable
    {
        return $this->datePublished;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_USERNAME => $this->getUsername(),
            self::FIELD_ID_USER => $this->getIdUser(),
            self::FIELD_RATING => $this->getRating(),
            self::FIELD_TITLE => $this->getTitle(),
            self::FIELD_REVIEW => $this->getReview(),
            self::FIELD_SPOILER => $this->getSpoiler(),
            self::FIELD_DATE_PUBLISHED => $this->getDatePublished()->getTimestamp(),
        ];
    }

    public static function buildFromArray(array $data): self
    {
        return new self(
            $data[self::FIELD_USERNAME],
            $data[self::FIELD_ID_USER],
            $data[self::FIELD_RATING],
            $data[self::FIELD_TITLE],
            $data[self::FIELD_REVIEW],
            $data[self::FIELD_SPOILER],
            (new \DateTimeImmutable())->setTimestamp($data[self::FIELD_DATE_PUBLISHED]),
        );
    }
}
