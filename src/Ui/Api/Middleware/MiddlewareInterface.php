<?php

namespace Ui\Api\Middleware;

use Ui\Api\Presenters\HttpResponsePresenter;

interface MiddlewareInterface
{
    public function __invoke(object $request): HttpResponsePresenter;
}
