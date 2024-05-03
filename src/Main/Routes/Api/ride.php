<?php

use Infrastructure\Http\HttpServerInterface;
use Main\Adapters\SlimRouteAdapter;
use Main\Factories\Controllers\Ride\AcceptRideControllerFactory;
use Main\Factories\Controllers\Ride\FinishRideControllerFactory;
use Main\Factories\Controllers\Ride\RequestRideControllerFactory;
use Main\Factories\Controllers\Ride\StartRideControllerFactory;

return function (HttpServerInterface $httpServer) {
    $prefix = '/api/v1/ride';
    $httpServer->register(
        method: "post",
        url: "{$prefix}/request",
        callback: new SlimRouteAdapter(
            controller: RequestRideControllerFactory::create()
        )
    );
    $httpServer->register(
        method: "post",
        url: "{$prefix}/accept",
        callback: new SlimRouteAdapter(
            controller: AcceptRideControllerFactory::create()
        )
    );
    $httpServer->register(
        method: "post",
        url: "{$prefix}/start",
        callback: new SlimRouteAdapter(
            controller: StartRideControllerFactory::create()
        )
    );
    $httpServer->register(
        method: "post",
        url: "{$prefix}/finish",
        callback: new SlimRouteAdapter(
            controller: FinishRideControllerFactory::create()
        )
    );
};
