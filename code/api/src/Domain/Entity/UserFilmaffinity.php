<?php

namespace App\Domain\Entity;

class UserFilmaffinity
{
    public const FIELD_USER_ID = 'userId';
    public const FIELD_USER_NAME = 'userName';
    public const FIELD_COOKIE = 'cookie';

    private int $userId;
    private string $userName;
    private ?string $cookie;

    public function __construct(int $userId, string $userName, ?string $cookie)
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->cookie = $cookie;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getCookie(): ?string
    {
        return $this->cookie;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_USER_ID => $this->getUserId(),
            self::FIELD_USER_NAME => $this->getUserName(),
            self::FIELD_COOKIE => $this->getCookie(),
        ];
    }
}
