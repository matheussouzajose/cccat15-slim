<?php

declare(strict_types=1);

namespace Main\Adapters;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Interfaces\ResponseInterface;
use Ui\Api\Controllers\Contracts\ControllerInterface;

class SlimRouteAdapter
{
    public function __construct(private readonly ControllerInterface $controller)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $requestData = array_merge(
            $args,
            $request->getParsedBody() ?? [],
            $request->getAttribute('user_id') ?? []
        );
        $httpResponse = ($this->controller)(request: (object)$requestData);
        $response->getBody()->write(json_encode($httpResponse->getBody()));
        $statusCode = $httpResponse->getStatus();
        if ($httpResponse->getStatus() === 0 || $httpResponse->getStatus() > 500) {
            $statusCode = 500;
        }
        return $response->withStatus($statusCode);
    }
}
