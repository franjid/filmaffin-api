<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\PlatformAvailability;

class PlatformCollection
{
    /** @var PlatformAvailability[] */
    private array $platforms;

    public function __construct(PlatformAvailability ...$platforms)
    {
        $this->platforms = $platforms;
    }

    public function getItems(): array
    {
        return $this->platforms;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->platforms as $platform) {
            $result[] = $platform->toArray();
        }

        return $result;
    }
}
