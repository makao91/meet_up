<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\LoginUserService;

use Tests\TestCase;
use App\Services\TokenService;
use App\Contracts\IAccessToken;
use App\Services\LoginUserService;
use App\Models\PersonalAccessToken;
use Illuminate\Hashing\HashManager;
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

    private HashManager $hash_manager_mock;
    private TokenService $token_service_mock;

    private IAccessToken $access_token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token_service_mock = \Mockery::mock(TokenService::class);
        $this->hash_manager_mock = \Mockery::mock(HashManager::class);
        $this->access_token = \Mockery::mock(IAccessToken::class);
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
        $user = $this->createUser(['email' => $email]);

        $request = $this->mockRequest($email, 'hashed_password');
        $this->hash_manager_mock->allows(['check' => true]);
        $this->token_service_mock->allows(['createToken' => $this->access_token]);
        $service = $this->createService();

        // WHEN
        $result = $service->login($request);

        // THEN
        $this->assertEquals($user->id, $result->getUserId());
        $this->assertInstanceOf(IAccessToken::class, $result->getToken());
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
        $this->createUser(['email' => $email]);

        $request = $this->mockRequest($email, 'invalid');
        $this->hash_manager_mock->allows(['check' => false]);
        $service = $this->createService();

        $this->expectException(InvalidLoginCredentialsException::class);

        // WHEN
        $service->login($request);

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
        $service = $this->createService();

        $this->expectException(InvalidLoginCredentialsException::class);

        // WHEN
        $result = $service->login($request);

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
        $request = $this->mockRequest($email, 'password');
        $this->createUser([
            'email' => $email,
            'email_verified_at' => null,
        ]);
        $this->hash_manager_mock->allows(['check' => true]);
        $service = $this->createService();

        $this->expectException(EmailNotVerifiedException::class);

        // WHEN
        $service->login($request);

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
        $service = $this->createService();

        // WHEN
        $result = $service->getUserDetails($user->id);

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
        $service = $this->createService();

        $this->expectException(NotFoundException::class);

        // WHEN
        $service->getUserDetails(1);

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
        $service = $this->createService();

        // WHEN
        $service->logout($access_token->plainTextToken);

        // THEN
        $this->assertDatabaseCount(PersonalAccessToken::class, 0);
        $this->assertDatabaseMissing(PersonalAccessToken::class, [
            'id' => $access_token->accessToken->id,
        ]);
    }
}
