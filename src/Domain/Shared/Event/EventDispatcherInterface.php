<?php

declare(strict_types=1);

namespace Domain\Shared\Event;

interface EventDispatcherInterface
{
    public function notify(EventInterface $event): void;

    public function register(string $eventName, EventHandlerInterface $eventHandler): void;

    public function unregister(string $eventName, EventHandlerInterface $eventHandler): void;

    public function unregisterAll(): void;
}
