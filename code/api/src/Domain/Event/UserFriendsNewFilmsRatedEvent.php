<?php

namespace App\Domain\Event;

class UserFriendsNewFilmsRatedEvent extends AbstractEvent
{
    public function __construct(
        private readonly int $userIdFilmaffinity
    ) {
    }

    public function getUserIdFilmaffinity(): int
    {
        return $this->userIdFilmaffinity;
    }
}
