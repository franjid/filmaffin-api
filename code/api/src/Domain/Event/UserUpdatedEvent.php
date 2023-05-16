<?php

namespace App\Domain\Event;

class UserUpdatedEvent extends AbstractEvent
{
    public function __construct(
        private readonly int $userIdFilmaffinity,
        private readonly string $cookieFilmaffinity
    ) {
    }

    public function getUserIdFilmaffinity(): int
    {
        return $this->userIdFilmaffinity;
    }

    public function getCookieFilmaffinity(): string
    {
        return $this->cookieFilmaffinity;
    }
}
