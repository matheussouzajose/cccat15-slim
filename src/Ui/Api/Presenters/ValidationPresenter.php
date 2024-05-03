<?php

declare(strict_types=1);

namespace Ui\Api\Presenters;

class ValidationPresenter
{
    public static function validate(array $body): HttpResponsePresenter
    {
        return new HttpResponsePresenter(statusCode: 422, body: $body);
    }
}
