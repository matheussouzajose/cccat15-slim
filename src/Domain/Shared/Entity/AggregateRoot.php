<?php

declare(strict_types=1);

namespace Domain\Shared\Entity;

use Domain\Shared\Event\EventDispatcher;
use Domain\Shared\Event\EventDispatcherInterface;

class AggregateRoot
{
    public function events(): EventDispatcherInterface
    {
        return EventDispatcher::events();
    }
}
