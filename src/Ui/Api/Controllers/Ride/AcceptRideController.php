<?php

declare(strict_types=1);

namespace Ui\Api\Controllers\Ride;

use Application\Ride\Command\AcceptRide;
use Application\Ride\Command\AcceptRideHandler;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Presenters\HttpResponsePresenter;
use Ui\Api\Presenters\ValidationPresenter;
use Ui\Api\Validation\ValidationInterface;

class AcceptRideController implements ControllerInterface
{
    public function __construct(
        private readonly AcceptRideHandler $handler,
        private readonly ValidationInterface $validation
    ) {
    }

    public function __invoke(object $request): HttpResponsePresenter
    {
        try {
            if ($validated = $this->validation->validate(request: $request)) {
                return ValidationPresenter::validate(body: $validated);
            }
            ($this->handler)(command: $this->createFromRequest(request: $request));
            return new HttpResponsePresenter(statusCode: 200, body: [
                'success' => true,
                'message' => 'Ride accepted.'
            ]);
        } catch (\Throwable $throwable) {
            $code = $throwable->getCode() !== 0 ? $throwable->getCode() : 500;
            return new HttpResponsePresenter(statusCode: $code, body: [
                'error' => $throwable->getMessage()
            ]);
        }
    }

    private function createFromRequest(object $request): AcceptRide
    {
        return new AcceptRide(rideId: $request->ride_id, driverId: $request->driver_id);
    }
}
