<?php

namespace App\Domain\Entity;

class UserFilmaffinity
{
    final public const FIELD_USER_ID = 'userId';
    final public const FIELD_USER_NAME = 'userName';
    final public const FIELD_COOKIE = 'cookie';

    public function __construct(
        private readonly int $userId,
        private readonly string $userName,
        private readonly ?string $cookie
    ) {
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
