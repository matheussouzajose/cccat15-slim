<?php

declare(strict_types=1);

namespace Ui\Api\Controllers\Ride;

use Application\Ride\Command\RequestRide;
use Application\Ride\Command\RequestRideHandler;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Presenters\HttpResponsePresenter;
use Ui\Api\Presenters\ValidationPresenter;
use Ui\Api\Validation\ValidationInterface;

class RequestRideController implements ControllerInterface
{
    public function __construct(
        private readonly RequestRideHandler $handler,
        private readonly ValidationInterface $validation
    ) {
    }

    public function __invoke(object $request): HttpResponsePresenter
    {
        try {
            if ($validated = $this->validation->validate(request: $request)) {
                return ValidationPresenter::validate(body: $validated);
            }
            $output = ($this->handler)(command: $this->createFromRequest(request: $request));
            return new HttpResponsePresenter(statusCode: 201, body: ['ride_id' => $output->rideId]);
        } catch (\Throwable $throwable) {
            return new HttpResponsePresenter(statusCode: (int)$throwable->getCode(), body: [
                'error' => $throwable->getMessage()
            ]);
        }
    }

    private function createFromRequest(object $request): RequestRide
    {
        return new RequestRide(
            passengerId: $request->passenger_id,
            fromLatitude: $request->from_latitude,
            fromLongitude: $request->from_longitude,
            toLatitude: $request->to_latitude,
            toLongitude: $request->to_longitude
        );
    }
}
