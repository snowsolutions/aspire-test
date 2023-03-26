<?php

namespace Tests\Feature\Controllers\Api;


use App\Models\Attributes\LoanApplication\Status;

class LoanApplicationControllerTest extends ApiTestCase
{
    /**
     * Test GET /api/loans (Logged in)
     */
    public function testIndex(): void
    {
        $token = $this->loginAsUser();

        $response = $this->get(self::USER_API_PREFIX . '/loans')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('loan_applications', $content['data']);
        $this->assertEquals(2, count($content['data']['loan_applications']));
    }

    /**
     * Test GET /api/loans (Not logged in)
     */
    public function testIndexNotLoggedIn(): void
    {
        $response = $this->get(self::USER_API_PREFIX . '/loans')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * Test GET /api/loans/{id} (Logged in)
     */
    public function testShow(): void
    {
        $token = $this->loginAsUser();

        $response = $this->get(self::USER_API_PREFIX . '/loans/1')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertEquals('PENDING', $content['data']['status']);
        $this->assertEquals(3, $content['data']['term']);
        $this->assertEquals("10000.00", $content['data']['amount']);
        $this->assertEquals("10000.00", $content['data']['remaining_amount']);
        $this->assertEquals(1, $content['data']['user_id']);
    }

    /**
     * Test GET /api/loans/{id} (Not logged in)
     */
    public function testShowNotLoggedIn(): void
    {
        $response = $this->get(self::USER_API_PREFIX . '/loans/{1}')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * Test GET /api/loans/{id} (Unauthorized)
     */
    public function testShowUnauthorized(): void
    {
        $token = $this->loginAsUser();

        $response = $this->get(self::USER_API_PREFIX . '/loans/3')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Test POST /api/loans (Success)
     */
    public function testStore()
    {
        $token = $this->loginAsUser();

        $jsonData = [
            'purpose' => 'Personal',
            'amount' => 10000,
            'term' => 3
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/loans', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('purpose', $content['data']);
        $this->assertArrayHasKey('amount', $content['data']);
        $this->assertArrayHasKey('remaining_amount', $content['data']);
        $this->assertArrayHasKey('term', $content['data']);
        $this->assertArrayHasKey('user_id', $content['data']);
        $this->assertArrayHasKey('status', $content['data']);
        $this->assertEquals(Status::PENDING->name, $content['data']['status']);
        $this->assertEquals(10000, $content['data']['amount']);
        $this->assertEquals(10000, $content['data']['remaining_amount']);
        $this->assertEquals(3, $content['data']['term']);
    }

    /**
     * Test POST /api/loans (Fail - without amount)
     */
    public function testStoreWithoutAmount()
    {
        $token = $this->loginAsUser();

        $jsonData = [
            'purpose' => 'Personal',
            'term' => 3
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/loans', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->assertEquals(422, $response->getStatusCode());

    }

    /**
     * Test POST /api/loans (Fail - without term)
     */
    public function testStoreWithoutTerm()
    {
        $token = $this->loginAsUser();

        $jsonData = [
            'purpose' => 'Personal',
            'amount' => 10000,
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/loans', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->assertEquals(422, $response->getStatusCode());

    }
}
