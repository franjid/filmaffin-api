<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\UserFilmaffinity;

class UserFilmaffinityCollection
{
    /** @var UserFilmaffinity[] */
    private readonly array $users;

    public function __construct(UserFilmaffinity ...$users)
    {
        $this->users = $users;
    }

    public function getItems(): array
    {
        return $this->users;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->users as $user) {
            $result[] = $user->toArray();
        }

        return $result;
    }
}
