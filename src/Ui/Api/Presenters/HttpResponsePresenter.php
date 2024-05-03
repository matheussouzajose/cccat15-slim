<?php

declare(strict_types=1);

namespace Ui\Api\Presenters;

use Ui\Api\Controllers\Contracts\HttpResponseInterface;

class HttpResponsePresenter implements HttpResponseInterface
{
    public function __construct(protected int $statusCode, protected $body)
    {
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }
}
