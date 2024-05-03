<?php

declare(strict_types=1);

namespace Domain\Shared\Event;

class EventDispatcher implements EventDispatcherInterface
{
    /** @var EventHandlerInterface[] */
    public array $eventsHandlers = [];

    public function notify(EventInterface $event): void
    {
        $eventName = get_class($event);
        if (isset($this->eventsHandlers[$eventName])) {
            foreach ($this->eventsHandlers[$eventName] as $eventsHandler) {
                $eventsHandler->handle($event);
            }
        }
    }

    public function register(string $eventName, EventHandlerInterface $eventHandler): void
    {
        if (!isset($this->eventsHandlers[$eventName])) {
            $this->eventsHandlers[$eventName] = [];
        }
        $this->eventsHandlers[$eventName][] = $eventHandler;
    }

    public function unregister(string $eventName, EventHandlerInterface $eventHandler): void
    {
        if (isset($this->eventsHandlers[$eventName])) {
            if (($key = array_search($eventHandler, (array)$this->eventsHandlers[$eventName], true)) !== false) {
                unset($this->eventsHandlers[$eventName][$key]);
            }
        }
    }

    public function unregisterAll(): void
    {
        $this->eventsHandlers = [];
    }

    public function registerEvents(string $eventName, array $eventHandlers): void
    {
        foreach ($eventHandlers as $eventHandler) {
            $this->register(eventName: $eventName, eventHandler: $eventHandler);
        }
    }

    public static function events(): EventDispatcherInterface
    {
        static $eventDispatcher;
        if (!$eventDispatcher) {
            $eventDispatcher = new EventDispatcher();
        }
        return $eventDispatcher;
    }
}
