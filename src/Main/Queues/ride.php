<?php

use Application\Contracts\AMQPInterface;
use Application\Ride\Command\UpdateRideProjection;
use Main\Factories\Command\Ride\UpdateRideProjectionHandlerFactory;

return function (AMQPInterface $queue) {
    $queue->consume(queue: 'rideCompleted', callback: function () {
        var_dump('ab');
    });
    $queue->consume(queue: 'rideStarted', callback: function ($data) {
        $handler = UpdateRideProjectionHandlerFactory::create();
        ($handler)(command: new UpdateRideProjection(rideId: $data->rideId));
    });
};
