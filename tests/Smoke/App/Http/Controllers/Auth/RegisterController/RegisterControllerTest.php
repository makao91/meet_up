<?php

declare(strict_types=1);

namespace Tests\Smoke\App\Http\Controllers\Auth\RegisterController;

use Tests\TestCase;
use App\Models\Descriptors\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterControllerTest extends TestCase
{
    use RegisterControllerTrait;
    use DatabaseTransactions;

    /**
     * @feature Auth
     * @scenario Register user
     * @case Confirm email using valid token
     *
     * @expectation Return success
     *
     * @test
     */
    public function register_confirmEmailUsingValidToken(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $token = '9a10a19c63cac4de1fb81bea8db65b8edc082834'; // sha1($email)
        $user = $this->createUser(['email' => $email, 'email_verified_at' => null]);
        $entry_data = [
            'user_id' => $user->id,
            'token' => $token,
        ];

        // WHEN
        $response = $this->postJson(route('user.auth.register.confirm-email'), $entry_data);

        // THEN
        $response->assertNoContent();
    }

    /**
     * @feature Auth
     * @scenario Register user
     * @case Confirm email using invalid token
     *
     * @expectation Return invalid confirm email token error
     *
     * @test
     */
    public function register_confirmEmailUsingInvalidToken(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $token = '123';
        $user = $this->createUser(['email' => $email, 'email_verified_at' => null]);
        $entry_data = [
            'user_id' => $user->id,
            'token' => $token,
        ];

        // WHEN
        $response = $this->postJson(route('user.auth.register.confirm-email'), $entry_data);

        // THEN
        $response->assertStatus(400);
        $response->assertJsonFragment(['message' => 'Invalid email verification token.']);
    }

    /**
     * @feature Auth
     * @scenario Register user
     * @case Invalid entry data provided
     *
     * @expectation Throw validation error
     *
     * @dataProvider invalidEntryDataProvider
     *
     * @test
     */
    public function register_invalidEntry(array $entry_data, string $invalid_element): void
    {
        // GIVEN

        // WHEN
        $response = $this->postJson(route('user.auth.register'), $entry_data);

        // THEN
        $response->assertUnprocessable();
        $this->assertArrayHasKey($invalid_element, $response->json('errors'));
    }

    /**
     * @feature Auth
     * @scenario Register user
     * @case Email already exists
     *
     * @expectation Throw email already registered error
     *
     * @test
     */
    public function register_emailExists(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $this->createUser([
            'email' => $email,
        ]);
        $entry_data = [
            'email' => $email,
            'name' => 'Test',
            'type' => UserType::USER,
            'password' => 'test1234&',
            'password_confirmation' => 'test1234&',
        ];

        // WHEN
        $response = $this->postJson(route('user.auth.register'), $entry_data);

        // THEN
        $response->assertStatus(409);
        $response->assertJson(['message' => 'Email already exists.']);
    }
}
