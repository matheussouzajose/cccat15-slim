<?php

declare(strict_types=1);

namespace Infrastructure\Mediator;

class RideStartedObserver2 implements ObserverInterface
{

    public function update(string $event, $data = null)
    {
        var_dump('aqui 2');
    }
}
