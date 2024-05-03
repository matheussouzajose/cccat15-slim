<?php

declare(strict_types=1);

namespace Main\Middlewares;

use Main\Adapters\SlimMiddlewareAdapter;
use Main\Factories\Middleware\AuthMiddlewareFactory;

class AuthMiddleware
{
    public function __invoke(): SlimMiddlewareAdapter
    {
        return new SlimMiddlewareAdapter(middleware: AuthMiddlewareFactory::create());
    }
}
