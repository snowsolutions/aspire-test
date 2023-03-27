<?php

namespace Tests\Feature\Controllers\Api\Admin;

use App\Models\Attributes\LoanApplication\Status;
use Tests\Feature\Controllers\Api\ApiTestCase;

class LoanApplicationControllerTest extends ApiTestCase
{
    /**
     * Test GET /api/admin/loans (Logged in)
     */
    public function testIndexLoggedIn(): void
    {
        $token = $this->loginAsAdmin();

        $response = $this->get(self::ADMIN_API_PREFIX.'/loans')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('loan_applications', $content['data']);
        $this->assertEquals(3, count($content['data']['loan_applications']));
    }

    /**
     * Test GET /api/admin/loans (Not logged in)
     */
    public function testIndexNotLoggedIn(): void
    {
        $response = $this->get(self::ADMIN_API_PREFIX.'/loans')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ',
        ]);
        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * Test POST /api/admin/approve/{id} (Logged in)
     */
    public function testApproveLoggedIn(): void
    {
        $token = $this->loginAsAdmin();

        $response = $this->post(self::ADMIN_API_PREFIX.'/loans/approve/1')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertEquals(Status::APPROVED->name, $content['data']['status']);
    }

    /**
     * Test POST /api/admin/approve/{id} (Not logged in)
     */
    public function testApproveNotLoggedIn(): void
    {
        $response = $this->post(self::ADMIN_API_PREFIX.'/loans/approve/1')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ',
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }
}
