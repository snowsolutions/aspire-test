<?php

namespace Tests\Unit\Policies;

use App\Models\LoanApplication;
use App\Models\User;
use App\Policies\LoanApplicationPolicy;
use Tests\TestCase;

class LoanApplicationPolicyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCanView()
    {
        $user = new User();
        $user->id  = 1;
        $application = new LoanApplication();
        $application->user_id = 1;
        $policy = new LoanApplicationPolicy();
        $result = $policy->view($user, $application);
        $this->assertTrue($result);
    }

    public function testCanNotView()
    {
        $user = new User();
        $user->id  = 1;
        $application = new LoanApplication();
        $application->user_id = 2;
        $policy = new LoanApplicationPolicy();
        $result = $policy->view($user, $application);
        $this->assertFalse($result);
    }
}
