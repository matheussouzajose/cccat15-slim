<?php

declare(strict_types=1);

namespace Infrastructure\Validation\RespectValidator\Auth;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Ui\Api\Validation\ValidationInterface;

class SignUpValidation implements ValidationInterface
{
    public function validate(object $request): ?array
    {
        try {
            $isDriver = $request->is_driver ?? false;
            $validator = v::attribute('name', v::stringType()->notEmpty())
                ->attribute('email', v::email()->notEmpty())
                ->attribute('cpf', v::cpf()->notEmpty())
                ->attribute('is_passenger', v::boolType())
                ->attribute('is_driver', v::boolType())
                ->attribute('car_plate', v::stringType()->notEmpty(), $isDriver);
            $validator->assert($request);
            return null;
        } catch (ValidationException $exception) {
            return [
                'errors' => $exception->getMessages()
            ];
        }
    }
}
