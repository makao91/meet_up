<?php

declare(strict_types=1);

namespace Tests\Smoke\App\Http\Controllers\Auth\RegisterController\ConfirmEmail;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

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
    public function register_confirmEmailUsingValidToken_success(): void
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
    public function register_confirmEmailUsingInvalidToken_400(): void
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
}
