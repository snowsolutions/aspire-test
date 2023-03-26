<?php

namespace Tests\Feature\Controllers\Api;

use App\Models\Attributes\Payment\Status;
use App\Repositories\Payment\PaymentRepository;

class PaymentControllerTest extends ApiTestCase
{
    /**
     * Test GET /api/payments (Logged in)
     * @return void
     */
    public function testIndex()
    {
        $token = $this->loginAsUser();

        $response = $this->get(self::USER_API_PREFIX . '/payments')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('payments', $content['data']);
        $this->assertEquals(8, count($content['data']['payments']));
    }

    /**
     * Test GET /api/payments (Not logged in)
     * @return void
     */
    public function testIndexNotLoggedIn()
    {
        $response = $this->get(self::USER_API_PREFIX . '/payments')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * Test GET /api/payments/{id} (Logged in)
     * @return void
     */
    public function testShowLoggedIn()
    {
        $token = $this->loginAsUser();

        $response = $this->get(self::USER_API_PREFIX . '/payments/1')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
    }

    /**
     * Test GET /api/payments/{id} (Unauthorized)
     * @return void
     */
    public function testShowUnauthorized()
    {
        $token = $this->loginAsUser();

        $response = $this->get(self::USER_API_PREFIX . '/payments/9')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Test GET /api/payments/{id} (Not logged in)
     * @return void
     */
    public function testShowNotLoggedIn()
    {
        $response = $this->get(self::USER_API_PREFIX . '/payments/1')->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * Test POST /api/payments/make_payment (Logged in)
     * @return void
     */
    public function testMakePaymentLoggedIn()
    {
        $token = $this->loginAsUser();

        $jsonData = [
            'id' => 4,
            'amount' => 4000
        ];
        $response = $this->postJson(self::USER_API_PREFIX . '/payments/make_payment', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertEquals(4000, $content['data']['amount']);
        $this->assertEquals(Status::PAID->name, $content['data']['status']);
    }

    /**
     * Test POST /api/payments/make_payment (Not logged in)
     * @return void
     */
    public function testMakePaymentNotLoggedIn()
    {
        $jsonData = [
            'id' => 4,
            'amount' => 4000
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/payments/make_payment', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * Test POST /api/payments/make_payment (Unauthorized)
     * @return void
     */
    public function testMakePaymentUnauthorized()
    {
        $token = $this->loginAsUser();

        $jsonData = [
            'id' => 9,
            'amount' => 4000
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/payments/make_payment', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Test POST /api/payments/make_payment (Without amount)
     * @return void
     */
    public function testMakePaymentWithoutAmount()
    {
        $token = $this->loginAsUser();

        $jsonData = [
            'id' => 4,
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/payments/make_payment', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * Test POST /api/payments/make_payment (Without PaymentId)
     * @return void
     */
    public function testMakePaymentWithoutPaymentId()
    {
        $token = $this->loginAsUser();

        $jsonData = [
            'amount' => 4000
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/payments/make_payment', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * Test POST /api/payments/make_payment (Once off payment)
     * @return void
     */
    public function testMakePaymentOnceOff()
    {
        $token = $this->loginAsUser();

        $jsonData = [
            'id' => 4,
            'amount' => 8000
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/payments/make_payment', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $paymentRepository = new PaymentRepository();

        $payment = $paymentRepository->findById(4);

        $application = $payment->application;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertEquals(8000, $content['data']['amount']);
        $this->assertEquals(Status::PAID->name, $content['data']['status']);
        $this->assertEquals(\App\Models\Attributes\LoanApplication\Status::PAID->name, $application->status);
        $this->assertEquals(0, $application->remaining_amount);
    }

    /**
     * Test POST /api/payments/make_payment (Pay greater amount)
     * @return void
     */
    public function testMakePaymentWithGreaterAmount()
    {
        $token = $this->loginAsUser();

        $jsonData = [
            'id' => 4,
            'amount' => 4000
        ];

        $response = $this->postJson(self::USER_API_PREFIX . '/payments/make_payment', $jsonData)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true);

        $paymentRepository = new PaymentRepository();

        $payment = $paymentRepository->findById(4);

        $application = $payment->application;

        $pendingPayment = $paymentRepository->findAllPendingByApplication($application->id)->first();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertEquals(4000, $content['data']['amount']);
        $this->assertEquals(Status::PAID->name, $content['data']['status']);
        $this->assertEquals(4000, $application->remaining_amount);
        $this->assertEquals(1000, $pendingPayment->amount);
    }
}
