<?php

declare(strict_types=1);

namespace Infrastructure\Validation\RespectValidator\Ride;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Ui\Api\Validation\ValidationInterface;

class RequestRideValidation implements ValidationInterface
{
    public function validate(object $request): ?array
    {
        try {
            $validator = v::attribute('passenger_id', v::stringType()->notEmpty())
                ->attribute('from_latitude', v::number()->notEmpty())
                ->attribute('from_longitude', v::number()->notEmpty())
                ->attribute('to_latitude', v::number()->notEmpty())
                ->attribute('to_longitude', v::number()->notEmpty());
            $validator->assert($request);
            return null;
        } catch (ValidationException $exception) {
            return [
                'errors' => $exception->getMessages()
            ];
        }
    }
}
