<?php

namespace Tests\Unit\Policies;

use App\Models\Payment;
use App\Models\User;
use App\Policies\PaymentPolicy;
use Tests\TestCase;

class PaymentPolicyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCanView()
    {
        $user = new User();
        $user->id = 1;
        $payment = new Payment();
        $payment->user_id = 1;
        $policy = new PaymentPolicy();
        $result = $policy->view($user, $payment);
        $this->assertTrue($result);
    }

    public function testCanNotView()
    {
        $user = new User();
        $user->id = 1;
        $payment = new Payment();
        $payment->user_id = 2;
        $policy = new PaymentPolicy();
        $result = $policy->view($user, $payment);
        $this->assertFalse($result);
    }

    public function testCanUpdate()
    {
        $user = new User();
        $user->id = 1;
        $payment = new Payment();
        $payment->user_id = 1;
        $policy = new PaymentPolicy();
        $result = $policy->update($user, $payment);
        $this->assertTrue($result);
    }

    public function testCanNotUpdate()
    {
        $user = new User();
        $user->id = 1;
        $payment = new Payment();
        $payment->user_id = 2;
        $policy = new PaymentPolicy();
        $result = $policy->update($user, $payment);
        $this->assertFalse($result);
    }
}
