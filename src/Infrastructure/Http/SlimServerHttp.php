<?php

declare(strict_types=1);

namespace Infrastructure\Http;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

class SlimServerHttp implements HttpServerInterface
{
    protected App $app;

    public function __construct(ContainerInterface $container = null)
    {
        if ($container) {
            AppFactory::setContainer(container: $container);
        }
        $this->app = AppFactory::create();
        $this->addBodyParsingMiddleware();
        $this->addContentTypeMiddleware();
        $this->addCorsMiddleware();
        $this->addErrorMiddleware();
        $this->addNoCacheMiddleware();
    }

    private function addBodyParsingMiddleware(): void
    {
        $this->app->addBodyParsingMiddleware();
    }

    private function addContentTypeMiddleware(): void
    {
        $this->app->add(function (Request $request, RequestHandler $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Content-Type', 'application/json');
        });
    }

    private function addCorsMiddleware(): void
    {
        $this->app->add(function (Request $request, RequestHandler $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', '*')
                ->withHeader('Access-Control-Allow-Methods', '*');
        });
    }

    private function addErrorMiddleware(): void
    {
        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setErrorHandler(
            [HttpNotFoundException::class, HttpMethodNotAllowedException::class],
            function (Request $request, \Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response->getBody()->write(json_encode(['error' => $exception->getMessage()]));
                return $response->withStatus($exception->getCode())->withHeader('Content-Type', 'application/json');
            }
        );
    }

    private function addNoCacheMiddleware(): void
    {
        $this->app->add(function (Request $request, RequestHandler $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('cache-control', 'no-store, no-cache, must-revalidate, proxy-revalidate')
                ->withHeader('pragma', 'no-cache')
                ->withHeader('expires', '0')
                ->withHeader('surrogate-control', 'no-store');
        });
    }

    public function register(string $method, string $url, $callback, array $middlewares = []): void
    {
        $route = $this->app->$method($url, $callback);
        foreach ($middlewares as $middleware) {
            $route->add($middleware);
        }
    }

    public function listen(): void
    {
        $this->app->run();
    }
}
