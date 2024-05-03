<?php

declare(strict_types=1);

namespace Ui\Api\Controllers\Contracts;

interface HttpResponseInterface
{
    public function __construct(int $statusCode, $body);

    public function getBody();

    public function getStatus();
}
