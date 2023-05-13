<?php

namespace App\Domain\Event;

class UserAddedEvent extends AbstractEvent
{
    private int $userIdFilmaffinity;
    private string $cookieFilmaffinity;

    public function __construct(
        int $userIdFilmaffinity,
        string $cookieFilmaffinity
    ) {
        $this->userIdFilmaffinity = $userIdFilmaffinity;
        $this->cookieFilmaffinity = $cookieFilmaffinity;
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
