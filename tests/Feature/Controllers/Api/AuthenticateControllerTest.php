<?php

namespace Tests\Feature\Controllers\Api;

class AuthenticateControllerTest extends ApiTestCase
{
    /**
     * Test POST /api/users/login (Success)
     */
    public function testLoginSuccess(): void
    {
        $jsonData = [
            'email' => 'user@test.com',
            'password' => '123456',
        ];

        $response = $this->postJson(self::USER_API_PREFIX.'/users/login', $jsonData);

        $content = $response->getContent();

        $content = json_decode($content, true);

        $response->assertStatus(200);

        $this->assertArrayHasKey('token', $content['data']);
    }

    /**
     * Test POST /api/users/login (Fail)
     */
    public function testLoginFail(): void
    {
        $jsonData = [
            'email' => 'user@test.com',
            'password' => 'incorrect_password',
        ];

        $response = $this->postJson(self::USER_API_PREFIX.'/users/login', $jsonData);

        $response->assertStatus(422);
    }

    /**
     * Test GET /api/users/info (Success)
     *
     * @return void
     */
    public function testInfo()
    {
        $token = $this->loginAsUser();

        $response = $this->get(self::USER_API_PREFIX.'/users/info')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('id', $content['data']);
        $this->assertArrayHasKey('name', $content['data']);
        $this->assertArrayHasKey('email', $content['data']);
        $this->assertArrayHasKey('email_verified_at', $content['data']);
        $this->assertArrayHasKey('created_at', $content['data']);
        $this->assertArrayHasKey('updated_at', $content['data']);
    }

    /**
     * Test GET /api/users/info (Fail)
     *
     * @return void
     */
    public function testInfoFail()
    {
        $response = $this->get(self::USER_API_PREFIX.'/users/info')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ',
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }
}
