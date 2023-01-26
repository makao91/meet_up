<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\RegisterService;

use Tests\TestCase;
use App\Models\User;
use App\Services\RegisterService;
use Illuminate\Hashing\HashManager;
use App\Exceptions\Http\EmailAlreadyExistsException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Exceptions\Http\InvalidConfirmEmailTokenException;

class RegisterServiceTest extends TestCase
{
    use DatabaseTransactions;
    use RegisterServiceTrait;

    private RegisterService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $hash_manager_mock = \Mockery::mock(HashManager::class);
        $hash_manager_mock->allows(['make' => 'password_hash']);
        $this->service = $this->app->make(RegisterService::class, [
            'hash_manager' => $hash_manager_mock,
        ]);
    }

    /**
     * @feature Auth
     * @scenario Register User
     * @case User is New
     *
     * @expectation create new User in database
     *
     * @test
     */
    public function register_newUser(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $name = 'name';
        $password = 'password';

        $request = $this->mockRequest($email, $name, $password);

        // WHEN
        $this->service->register($request);

        // THEN
        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    /**
     * @feature Auth
     * @scenario Register User
     * @case User exists in database
     *
     * @expectation throw exception EmailAlreadyExistsException
     *
     * @test
     */
    public function register_userExists(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $name = 'name';
        $password = 'password';

        $request = $this->mockRequest($email, $name, $password);
        $this->createUser([
            'email' => $email,
        ]);

        $this->expectException(EmailAlreadyExistsException::class);

        // WHEN
        $this->service->register($request);

        // THEN
    }

    /**
     * @feature Auth
     * @scenario Confirm Email
     * @case confirmed successfully
     *
     * @expectation update Users email_verified_at in database
     *
     * @test
     */
    public function confirmEmail_confirmed(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $email_token = sha1($email);
        $user = $this->createUser([
            'email' => $email,
            'email_verified_at' => null,
        ]);
        $request = $this->mockConfirmRequest($user->id, $email_token);

        // WHEN
        $this->service->confirmEmail($request);
        $user->refresh();

        // THEN
        $this->assertNotNull($user->email_verified_at);
    }

    /**
     * @feature Auth
     * @scenario Confirm Email
     * @case User already confirmed
     *
     * @expectation throw Invalid Confirm Email Token Exception
     * @test
     */
    public function confirmEmail_alreadyConfirmed(): void
    {
        // GIVEN
        $this->setFakeNow();
        $email = 'test@devpark.pl';
        $email_token = sha1($email);
        $user = $this->createUser([
            'email' => $email,
            'email_verified_at' => $this->now,
        ]);
        $request = $this->mockConfirmRequest($user->id, $email_token);

        $this->expectException(InvalidConfirmEmailTokenException::class);

        // WHEN
        $this->service->confirmEmail($request);

        // THEN
    }

    /**
     * @feature Auth
     * @scenario Confirm Email
     * @case User already confirmed
     *
     * @expectation throw Invalid Confirm Email Token Exception
     * @test
     */
    public function confirmEmail_invalidToken(): void
    {
        // GIVEN
        $this->setFakeNow();
        $email = 'test@devpark.pl';
        $email_token = 'invalid_token';
        $user = $this->createUser([
            'email' => $email,
            'email_verified_at' => null,
        ]);
        $request = $this->mockConfirmRequest($user->id, $email_token);

        $this->expectException(InvalidConfirmEmailTokenException::class);

        // WHEN
        $this->service->confirmEmail($request);

        // THEN
    }
}
