<?php

declare(strict_types=1);

namespace Domain\Shared\Event;

interface EventHandlerInterface
{
    public function handle(EventInterface $event): void;
}
