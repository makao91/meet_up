<?php

declare(strict_types=1);

namespace Tests\Integration\App\Services\LoginUserService;

use Mockery as m;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Contracts\ILoginRequest;
use App\Contracts\ITokenRequest;
use App\Models\PersonalAccessToken;
use Laravel\Sanctum\NewAccessToken;

trait LoginUserServiceTrait
{
    public function mockRequest(string $email, string $password): ILoginRequest
    {
        $mock = m::mock(ILoginRequest::class);
        $mock->allows([
            'getEmail' => $email,
            'getPlainPassword' => $password,
        ]);

        return $mock;
    }

    public function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    public function createToken(User $user, string $name = 'test', array $abilities = []): NewAccessToken
    {
        $generated_token = Str::random(40);

        /** @var PersonalAccessToken $token */
        $token = $user->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $generated_token),
            'abilities' => $abilities,
            'expires_at' => Carbon::now()->addMinutes(1),
            'refresh_expires_at' => Carbon::now()->addMinutes(3),
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$generated_token);
    }

    public function prepareILogoutRequest(string $access_token): ITokenRequest
    {
        return new class($access_token) implements ITokenRequest {
            public function __construct(private readonly string $access_token)
            {
            }

            public function getToken(): string
            {
                return $this->access_token;
            }
        };
    }
}
