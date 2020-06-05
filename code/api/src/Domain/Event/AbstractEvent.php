<?php

namespace App\Domain\Event;

use App\Domain\Interfaces\Event\EventInterface;

class AbstractEvent implements EventInterface
{
    final public function getEventName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
