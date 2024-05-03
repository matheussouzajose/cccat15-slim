<?php

declare(strict_types=1);

namespace Main\Adapters;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Ui\Api\Middleware\MiddlewareInterface;

class SlimMiddlewareAdapter
{
    public function __construct(protected MiddlewareInterface $middleware)
    {
    }

    public function __invoke(Request &$request, RequestHandler $handler): ResponseInterface
    {
        $httpResponse = ($this->middleware)((object)$request->getHeaders());
        if ($httpResponse->getStatus() === 200) {
            $body = $httpResponse->getBody();
            if (isset($body['user_id'])) {
                $request = $request->withAttribute('user_id', $body);
            }
            return $handler->handle($request);
        }

        $response = new Response();
        $errorResponse = ['error' => $httpResponse->getBody()];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withStatus($httpResponse->getStatus());
    }
}
