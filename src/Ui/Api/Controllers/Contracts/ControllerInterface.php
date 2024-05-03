<?php

namespace Ui\Api\Controllers\Contracts;

use Ui\Api\Presenters\HttpResponsePresenter;

interface ControllerInterface
{
    public function __invoke(object $request): HttpResponsePresenter;
}
