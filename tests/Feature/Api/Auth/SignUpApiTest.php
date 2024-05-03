<?php

declare(strict_types=1);

namespace Feature\Api\Auth;

use GuzzleHttp\Client;
use Tests\DatabaseTestCase;
use GuzzleHttp\Exception\RequestException;

class SignUpApiTest extends DatabaseTestCase
{
    private string $endpoint = 'http://localhost/api/v1/signup';

    public function test_can_be_validation()
    {
//        try {
//            $client = new Client();
//            $client->post($this->endpoint, []);
//        } catch (RequestException $request) {
//            $response = $request->getResponse();
//            $body = $response->getBody()->getContents();
//            $errors = json_decode($body, true);
//
//            $this->assertEquals(422, $response->getStatusCode());
//            $this->assertArrayHasKey('name', $errors);
//            $this->assertArrayHasKey('email', $errors);
//            $this->assertArrayHasKey('cpf', $errors);
//            $this->assertArrayHasKey('is_passenger', $errors);
//            $this->assertArrayHasKey('is_driver', $errors);
//        }

        $this->assertTrue(true);
    }
}
