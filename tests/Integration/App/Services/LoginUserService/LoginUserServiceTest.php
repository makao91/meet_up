<?php

declare(strict_types=1);

namespace Tests\Integration\App\Services\LoginUserService;

use Tests\TestCase;
use App\Services\LoginUserService;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Http\NotFoundException;
use App\Exceptions\Http\EmailNotVerifiedException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Exceptions\Http\InvalidLoginCredentialsException;

class LoginUserServiceTest extends TestCase
{
    use DatabaseTransactions;
    use LoginUserServiceTrait;

    private LoginUserService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(LoginUserService::class);
    }

    /**
     * @feature Auth
     * @scenario Login user
     * @case Login with valid user credentials
     *
     * @expectation access token saved in database
     *
     * @test
     */
    public function login_loginWithValidUserCredentials(): void
    {
        // GIVEN
        $this->setFakeNow('2022-06-22');
        $email = 'test@devpark.pl';
        $password = '123test';
        $hash = Hash::make($password);
        $expected_token_name = 'test@devpark.pl-1655856000';

        $request = $this->mockRequest($email, $password);
        $user = $this->createUser(['email' => $email, 'password' => $hash]);

        // WHEN
        $this->service->login($request);

        // THEN
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => $user->id,
            'name' => $expected_token_name,
        ]);
    }

    /**
     * @feature Auth
     * @scenario Login user
     * @case Login with invalid user password
     *
     * @expectation Throw invalid login credentials error
     *
     * @test
     */
    public function login_loginWithInvalidUserPassword(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $password = '123test';
        $hash = Hash::make($password);

        $request = $this->mockRequest($email, 'invalid');
        $this->createUser(['email' => $email, 'password' => $hash]);

        $this->expectException(InvalidLoginCredentialsException::class);

        // WHEN
        $this->service->login($request);

        // THEN
    }

    /**
     * @feature Auth
     * @scenario Login user
     * @case Login with non-existing email
     *
     * @expectation Throw invalid login credentials error
     *
     * @test
     */
    public function login_loginWithNonExistingEmail(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $password = '123test';

        $request = $this->mockRequest($email, $password);
        $this->createUser(['email' => 'foo@bar.pl']);

        $this->expectException(InvalidLoginCredentialsException::class);

        // WHEN
        $result = $this->service->login($request);

        // THEN
    }

    /**
     * @feature Auth
     * @scenario Login user
     * @case Email not verified
     *
     * @expectation Throw email not verified
     *
     * @test
     */
    public function login_emailNotVerified(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $password = '123test';
        $hash = Hash::make($password);
        $request = $this->mockRequest($email, $password);
        $this->createUser([
            'email' => $email,
            'password' => $hash,
            'email_verified_at' => null,
        ]);

        $this->expectException(EmailNotVerifiedException::class);

        // WHEN
        $result = $this->service->login($request);

        // THEN
    }

    /**
     * @feature Auth
     * @scenario Get User Details
     * @case User exist
     *
     * @expectation return User
     *
     * @test
     */
    public function getUserDetails_userExists(): void
    {
        // GIVEN
        $email = 'test@devpark.pl';
        $password = '123test';
        $hash = Hash::make($password);
        $user = $this->createUser([
            'email' => $email,
            'password' => $hash,
            'email_verified_at' => null,
        ]);

        // WHEN
        $result = $this->service->getUserDetails($user->id);

        // THEN
        $this->assertEquals($user->id, $result->id);
    }

    /**
     * @feature Auth
     * @scenario Get User Details
     * @case User not exist
     *
     * @expectation throw not found exception
     *
     * @test
     */
    public function getUserDetails_userNotExists(): void
    {
        // GIVEN
        $this->expectException(NotFoundException::class);

        // WHEN
        $this->service->getUserDetails(1);

        // THEN
    }

    /**
     * @feature Auth
     * @scenario Logout user
     * @case Perform logout action
     *
     * @expectation Remove currently used token from database
     *
     * @test
     */
    public function logout_performLogoutAction(): void
    {
        // GIVEN
        $user = $this->createUser();
        $access_token = $this->createToken($user);

        // WHEN
        $this->service->logout($access_token->plainTextToken);

        // THEN
        $this->assertDatabaseCount(PersonalAccessToken::class, 0);
        $this->assertDatabaseMissing(PersonalAccessToken::class, [
            'id' => $access_token->accessToken->id,
        ]);
    }
}
