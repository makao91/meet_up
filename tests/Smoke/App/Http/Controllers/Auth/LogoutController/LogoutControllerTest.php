<?php

declare(strict_types=1);

namespace Tests\Smoke\App\Http\Controllers\Auth\LogoutController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogoutControllerTest extends TestCase
{
    use DatabaseTransactions;
    use LogoutControllerTrait;

    /**
     * @feature Auth
     * @scenario Logout user
     * @case Perform logout action by not logged User
     *
     * @expectation Destroy token and return success response
     *
     * @test
     */
    public function logout_performLogoutActionByNotLoggedUser(): void
    {
        // GIVEN

        // WHEN
        $response = $this->postJson(route('user.auth.logout'));

        // THEN
        $response->assertUnauthorized();
    }

    /**
     * @feature Auth
     * @scenario Logout user
     * @case Perform logout action
     *
     * @expectation Destroy token and return success response
     *
     * @test
     */
    public function logout_performLogoutActionByCandidate(): void
    {
        // GIVEN
        $user = $this->createAndBeUser();
        $this->createAndUseAccessToken($user);

        // WHEN
        $response = $this->postJson(route('user.auth.logout'));

        // THEN
        $response->assertNoContent();
    }
}
