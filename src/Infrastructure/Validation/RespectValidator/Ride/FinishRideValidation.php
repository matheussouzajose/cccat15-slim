<?php

declare(strict_types=1);

namespace Infrastructure\Validation\RespectValidator\Ride;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Ui\Api\Validation\ValidationInterface;

class FinishRideValidation implements ValidationInterface
{
    public function validate(object $request): ?array
    {
        try {
            $validator = v::attribute('ride_id', v::stringType()->notEmpty());
            $validator->assert($request);
            return null;
        } catch (ValidationException $exception) {
            return [
                'errors' => $exception->getMessages()
            ];
        }
    }
}
