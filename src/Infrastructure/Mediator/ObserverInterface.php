<?php

namespace Infrastructure\Mediator;

interface ObserverInterface
{
    public function update(string $event, $data = null);
}
