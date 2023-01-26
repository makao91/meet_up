<?php

declare(strict_types=1);

namespace Tests\Smoke\App\Http\Controllers\Auth\LoginController\Login;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

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
    public function login_loginWithValidCredentials_validResponse(): void
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
    public function login_loginWithInvalidUserPassword_unauthorized(): void
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
     * @expectation Return 422, invalid email
     *
     * @test
     */
    public function login_loginWithInvalidEmail_validationError(): void
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
