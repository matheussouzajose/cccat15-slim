<?php

declare(strict_types=1);

namespace Infrastructure\Mediator;

use Domain\Shared\Event\EventDispatcher;
use Domain\Shared\Event\EventDispatcherInterface;

class Mediator
{
    public function events(): EventDispatcherInterface
    {
        return EventDispatcher::events();
    }

    //    /** @var ObserverInterface[] */
//    private array $observers = [];
//
//    public function __construct()
//    {
//        $this->observers["*"] = [];
//    }
//
//    private function initEventGroup(string &$event = "*"): void
//    {
//        if (!isset($this->observers[$event])) {
//            $this->observers[$event] = [];
//        }
//    }
//
//    private function getEventObservers(string $event = "*"): array
//    {
//        $this->initEventGroup($event);
//        $group = $this->observers[$event];
//        $all = $this->observers["*"];
//        return array_merge((array)$group, (array)$all);
//    }
//
//    public function attach(ObserverInterface $observer, string $event = "*"): void
//    {
//        if (!isset($this->observers[$event])) {
//            $this->observers[$event] = [];
//        }
//        $this->observers[$event][] = $observer;
//    }
//
//    public function detach(ObserverInterface $observer, string $event = "*"): void
//    {
//        foreach ($this->getEventObservers($event) as $key => $s) {
//            if ($s === $observer) {
//                unset($this->observers[$event][$key]);
//            }
//        }
//    }
//
//    public function trigger(string $event, $data = null): void
//    {
//        foreach ($this->getEventObservers($event) as $observer) {
//            $observer->update($event, $data);
//        }
//    }
//
//    public static function events(): Mediator
//    {
//        static $eventDispatcher;
//        if (!$eventDispatcher) {
//            $eventDispatcher = new Mediator();
//        }
//        return $eventDispatcher;
//    }
}
