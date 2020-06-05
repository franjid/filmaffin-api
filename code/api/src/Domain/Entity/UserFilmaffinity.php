<?php

namespace App\Domain\Entity;

class UserFilmaffinity
{
    public const FIELD_USER_ID = 'user_id';
    public const FIELD_COOKIE = 'cookie';

    private int $userId;
    private string $cookie;

    public function __construct(int $userId, string $cookie)
    {
        $this->userId = $userId;
        $this->cookie = $cookie;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCookie(): string
    {
        return $this->cookie;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_USER_ID => $this->getUserId(),
            self::FIELD_COOKIE => $this->getCookie(),
        ];
    }
}
