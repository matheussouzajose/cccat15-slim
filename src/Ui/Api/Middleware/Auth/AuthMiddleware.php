<?php

declare(strict_types=1);

namespace Ui\Api\Middleware\Auth;

use Ui\Api\Middleware\MiddlewareInterface;
use Ui\Api\Presenters\HttpResponsePresenter;

class AuthMiddleware implements MiddlewareInterface
{

    public function __invoke(object $request): HttpResponsePresenter
    {
//        try {
//            if ( isset($request->Authorization) ) {
//                $accessToken = $this->removeBearer($request->Authorization[0]);
//                if ( $this->accountRepository->checkByToken(token: $accessToken) ) {
//                    $userId = $this->encrypter->decrypt(ciphertext: $accessToken);
//                    return HttpHelper::ok(['user_id' => $userId]);
//                }
//            }
//            return HttpHelper::forbiden(error: 'Access Denied');
//        } catch (\Exception $exception) {
//            return HttpHelper::serverError(error: $exception);
//        }
        try {
            return new HttpResponsePresenter(statusCode: 401, body: 'Access Denied');
        } catch (\Throwable $throwable) {
            return new HttpResponsePresenter(statusCode: $throwable->getCode(), body: [
                'error' => $throwable->getMessage()
            ]);
        }

    }

    private function removeBearer(string $token): string
    {
        $accessToken = $token;
        if ( str_starts_with($accessToken, 'Bearer ') ) {
            $accessToken = substr($accessToken, 7);
        }
        return $accessToken;
    }
}
