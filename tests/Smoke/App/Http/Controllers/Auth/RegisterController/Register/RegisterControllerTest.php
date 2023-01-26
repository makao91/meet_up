<?php

declare(strict_types=1);

namespace Tests\Smoke\App\Http\Controllers\Auth\RegisterController\Register;

use App\Models\Descriptors\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RegisterControllerTrait;
    use DatabaseTransactions;
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
    public function register_invalidEntry_validationError(array $entry_data, string $invalid_element): void
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
    public function register_emailExists_throwError(): void
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
