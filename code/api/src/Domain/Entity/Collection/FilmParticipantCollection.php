<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\FilmParticipant;

class FilmParticipantCollection
{
    /** @var FilmParticipant[] */
    private readonly array $participants;

    public function __construct(?FilmParticipant ...$participants)
    {
        $this->participants = $participants;
    }

    public function getItems(): array
    {
        return $this->participants;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->participants as $participant) {
            $result[] = $participant->toArray();
        }

        return $result;
    }
}
