<?php

namespace App\Domain\Event;

class UserFriendsNewFilmsRatedEvent extends AbstractEvent
{
    private int $userIdFilmaffinity;

    public function __construct(
        int $userIdFilmaffinity
    )
    {
        $this->userIdFilmaffinity = $userIdFilmaffinity;
    }

    public function getUserIdFilmaffinity(): int
    {
        return $this->userIdFilmaffinity;
    }
}
