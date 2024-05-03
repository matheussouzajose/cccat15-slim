<?php

use Infrastructure\Http\HttpServerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Interfaces\ResponseInterface;

return function (HttpServerInterface $httpServer) {
    $httpServer->register(method: "get", url: "/ping", callback: function (ServerRequestInterface $request, ResponseInterface $response, $args) {
        $response->getBody()->write(json_encode(['success' => true]));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    });
};
