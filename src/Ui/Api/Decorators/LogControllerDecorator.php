<?php

declare(strict_types=1);

namespace Ui\Api\Decorators;

use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Presenters\HttpResponsePresenter;

class LogControllerDecorator implements ControllerInterface
{
    public function __construct(private readonly ControllerInterface $controller)
    {
    }

    public function __invoke(object $request): HttpResponsePresenter
    {
        $httpResponse = ($this->controller)($request);
        if ( $httpResponse->getStatus() > 500 ) {
//            var_dump($httpResponse->getBody());
        }
        return $httpResponse;
    }
}
