<?php

namespace App\Infrastructure\Queue\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

class UniqueIdStamp implements StampInterface
{
    private $uniqueId;

    public function __construct()
    {
        $this->uniqueId = uniqid('', true);
    }

    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }
}
