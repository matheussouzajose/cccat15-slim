<?php

declare(strict_types=1);

namespace Ui\Api\Validation;

interface ValidationInterface
{
    public function validate(object $request): ?array;
}
