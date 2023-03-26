<?php

namespace Tests\Feature\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    const USER_API_PREFIX = '/api';
    const ADMIN_API_PREFIX = '/api/admin';

    protected function loginAsUser()
    {
        $jsonData = [
            'email' => 'user@test.com',
            'password' => '123456'
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/users/login', $jsonData);

        $content = $response->getContent();

        $content = json_decode($content, true);

        return $content['data']['token'];
    }

    protected function loginAsAdmin()
    {
        $jsonData = [
            'email' => 'admin@admin.com',
            'password' => '654321'
        ];

        $response = $this->postJson(self::ADMIN_API_PREFIX . '/login', $jsonData);

        $content = $response->getContent();

        $content = json_decode($content, true);

        return $content['data']['token'];
    }
}
