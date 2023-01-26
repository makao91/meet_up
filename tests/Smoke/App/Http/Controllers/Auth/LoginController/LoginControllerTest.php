<?php

declare(strict_types=1);

namespace Tests\Smoke\App\Http\Controllers\Auth\LoginController;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions;
    use LoginControllerTrait;

    /**
     * @feature Auth
     * @scenario Login user
     * @case Login with valid user credentials
     *
     * @expectation Return valid response structure
     *
     * @test
     */
    public function login_loginWithValidUserCredentialsAsCandidate(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $password = 'test123';

        $this->createUser([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $request_body = [
            'email' => $email,
            'password' => $password,
        ];

        // WHEN
        $response = $this->postJson(route('user.auth.login'), $request_body);

        // THEN
        $response->assertOk();
        $response->assertJsonStructure($this->getExpectedLoginJsonStructure());
    }

    /**
     * @feature Auth
     * @scenario Login user
     * @case Login with invalid user password
     *
     * @expectation Return valid response structure
     *
     * @test
     */
    public function login_loginWithInvalidUserPassword(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $password = 'test123';

        $this->createUser([
            'email' => $email,
        ]);

        $request_body = [
            'email' => $email,
            'password' => $password,
        ];

        // WHEN
        $response = $this->postJson(route('user.auth.login'), $request_body);

        // THEN
        $response->assertUnauthorized();
        $this->assertEquals('Invalid email or password provided.', $response->json('message'));

        $this->assertGuest();
    }

    /**
     * @feature Auth
     * @scenario Login user
     * @case Login with invalid user email
     *
     * @expectation Return 422, ivalid email
     *
     * @test
     */
    public function login_loginWithInvalidEmail(): void
    {
        // GIVEN
        $email = 'testdevpark.pl';
        $password = 'test123';

        $this->createUser([
            'email' => $email,
        ]);

        $request_body = [
            'email' => $email,
            'password' => $password,
        ];

        // WHEN
        $response = $this->postJson(route('user.auth.login'), $request_body);

        // THEN
        $response->assertJsonValidationErrors([
            'email' => 'The email must be a valid email address.',
        ]);
        $response->assertStatus(422);
    }
}
