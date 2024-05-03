<?php

use Infrastructure\Http\HttpServerInterface;
use Main\Adapters\SlimRouteAdapter;
use Main\Factories\Controllers\Account\GetAccountByIdControllerFactory;

return function (HttpServerInterface $httpServer) {
    $httpServer->register(
        method: "get",
        url: "/api/v1/account/{id}",
        callback: new SlimRouteAdapter(controller: GetAccountByIdControllerFactory::create())
    );
};
