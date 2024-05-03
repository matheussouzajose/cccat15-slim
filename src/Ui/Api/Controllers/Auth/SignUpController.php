<?php

declare(strict_types=1);

namespace Ui\Api\Controllers\Auth;

use Application\Auth\Command\SignUp;
use Application\Auth\Command\SignUpHandler;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Presenters\HttpResponsePresenter;
use Ui\Api\Presenters\ValidationPresenter;
use Ui\Api\Validation\ValidationInterface;

class SignUpController implements ControllerInterface
{
    public function __construct(
        private readonly SignUpHandler $commandHandler,
        private readonly ValidationInterface $validation,
    ) {
    }

    public function __invoke(object $request): HttpResponsePresenter
    {
        try {
            if ($validated = $this->validation->validate(request: $request)) {
                return ValidationPresenter::validate(body: $validated);
            }
            $output = ($this->commandHandler)(command: $this->createFromRequest(request: $request));
            return new HttpResponsePresenter(statusCode: 201, body: [
                'account_id' => $output->accountId
            ]);
        } catch (\Throwable $throwable) {
            return new HttpResponsePresenter(statusCode: (int)$throwable->getCode(), body: [
                'error' => $throwable->getMessage()
            ]);
        }
    }

    private function createFromRequest(object $request): SignUp
    {
        return new SignUp(
            name: $request->name,
            email: $request->email,
            cpf: $request->cpf,
            isPassenger: $request->is_passenger,
            isDriver: $request->is_driver,
            carPlate: $request->car_plate ?? null
        );
    }
}
