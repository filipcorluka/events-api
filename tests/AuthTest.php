<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{

    public function test_authenticate()
    {
        $response = $this->json('GET', '/api/login');
        $this->assertEquals(422, $this->response->status());
        $this->assertObjectHasAttribute('email', json_decode($this->response->content()));
        $this->assertObjectHasAttribute('password', json_decode($this->response->content()));
    }

    public function test_authenticate_with_credentials()
    {
        $userData = [
            'email'    => 'test@events.com',
            'password' => 'TestPW1?'
        ];

        $response = $this->json('GET', '/api/login', $userData);
        $this->assertEquals(200, $this->response->status());
        $this->assertObjectHasAttribute('api_token', json_decode($this->response->content()));

    }
}
