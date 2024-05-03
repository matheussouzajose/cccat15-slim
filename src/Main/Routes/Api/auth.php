<?php

use Infrastructure\Http\HttpServerInterface;
use Main\Adapters\SlimRouteAdapter;
use Main\Factories\Controllers\Auth\SignUpControllerFactory;

return function (HttpServerInterface $httpServer) {
    $httpServer->register(
        method: "post",
        url: "/api/v1/signup",
        callback: new SlimRouteAdapter(controller: SignUpControllerFactory::create())
    );
};
