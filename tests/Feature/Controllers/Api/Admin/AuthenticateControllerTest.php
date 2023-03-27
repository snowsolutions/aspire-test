<?php

namespace Tests\Feature\Controllers\Api\Admin;

use Tests\Feature\Controllers\Api\ApiTestCase;

class AuthenticateControllerTest extends ApiTestCase
{
    /**
     * Test POST /api/admin/login (Success)
     */
    public function testLoginSuccess(): void
    {
        $jsonData = [
            'email' => 'admin@admin.com',
            'password' => '654321',
        ];

        $response = $this->postJson(self::ADMIN_API_PREFIX.'/login', $jsonData);

        $content = $response->getContent();

        $content = json_decode($content, true);

        $response->assertStatus(200);

        $this->assertArrayHasKey('token', $content['data']);
    }

    /**
     * Test POST /api/admin/login (Fail)
     */
    public function testLoginFail(): void
    {
        $jsonData = [
            'email' => 'admin@admin.com',
            'password' => 'incorrect_password',
        ];

        $response = $this->postJson(self::ADMIN_API_PREFIX.'/login', $jsonData);

        $response->assertStatus(422);
    }

    /**
     * Test GET /api/admin/info (Success)
     *
     * @return void
     */
    public function testInfo()
    {
        $token = $this->loginAsAdmin();

        $response = $this->get(self::ADMIN_API_PREFIX.'/info')->withHeaders([
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
     * Test GET /api/admin/info (Fail)
     *
     * @return void
     */
    public function testInfoFail()
    {
        $response = $this->get(self::ADMIN_API_PREFIX.'/info')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ',
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }
}
