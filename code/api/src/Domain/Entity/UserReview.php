<?php

namespace App\Domain\Entity;

use DateTimeImmutable;

class UserReview
{
    public const FIELD_USERNAME = 'username';
    public const FIELD_ID_USER = 'idUser';
    public const FIELD_RATING = 'rating';
    public const FIELD_TITLE = 'title';
    public const FIELD_REVIEW = 'review';
    public const FIELD_SPOILER = 'spoiler';
    public const FIELD_DATE_PUBLISHED = 'datePublished';

    private string $username;
    private int $idUser;
    private ?int $rating;
    private string $title;
    private string $review;
    private ?string $spoiler;
    private DateTimeImmutable $datePublished;

    public function __construct(
        string $username,
        int $idUser,
        ?int $rating,
        string $title,
        string $review,
        ?string $spoiler,
        DateTimeImmutable $datePublished
    )
    {
        $this->username = $username;
        $this->idUser = $idUser;
        $this->rating = $rating;
        $this->title = $title;
        $this->review = $review;
        $this->spoiler = $spoiler;
        $this->datePublished = $datePublished;
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

    public function getDatePublished(): DateTimeImmutable
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
            (new DateTimeImmutable())->setTimestamp($data[self::FIELD_DATE_PUBLISHED]),
        );
    }
}
