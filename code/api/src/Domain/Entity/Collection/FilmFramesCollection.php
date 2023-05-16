<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\FilmFrame;

class FilmFramesCollection
{
    /** @var FilmFrame[] */
    private readonly array $filmFrames;

    public function __construct(FilmFrame ...$filmFrames)
    {
        $this->filmFrames = $filmFrames;
    }

    public function getItems(): array
    {
        return $this->filmFrames;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->filmFrames as $filmFrame) {
            $result[] = $filmFrame->toArray();
        }

        return $result;
    }
}
