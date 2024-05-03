<?php

declare(strict_types=1);

namespace Main\Factories\Middleware;

use Ui\Api\Middleware\Auth\AuthMiddleware;
use Ui\Api\Middleware\MiddlewareInterface;

class AuthMiddlewareFactory
{
    public static function create(?string $role = null): MiddlewareInterface
    {
//        return new AuthMiddleware(
//            accountRepository: AccountRepositoryFactory::create(),
//            encrypter: new JwtAdapter(getenv('SECRET_JWT')),
//            role: $role
//        );

        return new AuthMiddleware();
    }
}
