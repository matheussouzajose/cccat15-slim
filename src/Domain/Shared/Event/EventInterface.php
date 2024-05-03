<?php

declare(strict_types=1);

namespace Domain\Shared\Event;

interface EventInterface
{
    public function dateTimeOccurred(): \DateTimeInterface;

    public function eventData();

    public function getEventName(): string;
}
