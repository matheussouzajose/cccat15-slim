<?php

declare(strict_types=1);

namespace Ui\Api\Controllers\Account;

use Application\Account\Query\GetAccountByIdQuery;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Presenters\HttpResponsePresenter;

class GetAccountByIdController implements ControllerInterface
{
    public function __construct(private readonly GetAccountByIdQuery $query)
    {
    }

    public function __invoke(object $request): HttpResponsePresenter
    {
        try {
            $output = ($this->query)($request->id);
            return new HttpResponsePresenter(statusCode: 200, body: $this->body(output: $output));
        } catch (\Throwable $throwable) {
            $code = $throwable->getCode() !== 0 ? $throwable->getCode() : 500;
            return new HttpResponsePresenter(statusCode: $code, body: [
                'error' => $throwable->getMessage()
            ]);
        }
    }

    private function body(object $output): array
    {
        return [
            'account_id' => $output->accountId,
            'name' => $output->name,
            'email' => $output->email,
            'cpf' => $output->cpf,
            'car_plate' => $output->carPlate,
            'is_passenger' => (bool)$output->isPassenger,
            'is_driver' => (bool)$output->isDriver,
            'created_at' => (new \DateTime($output->createdAt))->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime($output->updatedAt))->format('Y-m-d H:i:s'),
        ];
    }
}
