<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Carbon;
use App\Models\PersonalAccessToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected Carbon $now;

    protected function setUp(): void
    {
        parent::setUp();

        if (!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }
    }

    public function createAndBeUser(array $attributes = [], array $abilities = ['*']): User
    {
        $user = $this->createUser($attributes);
        Sanctum::actingAs($user, $abilities);

        return $user;
    }

    public function createUser(array $attributes = []): User
    {
        return User::factory()
            ->create($attributes)
        ;
    }

    public function createAndUseAccessToken(
        User $user,
        ?string $token = null,
        string $name = 'test',
        array $abilities = ['*']
    ): PersonalAccessToken {
        $generated_token = $token ?? Str::random(40);

        /** @var PersonalAccessToken $token */
        $token = PersonalAccessToken::make([
            'name' => $name,
            'token' => hash('sha256', $generated_token),
            'abilities' => $abilities,
            'expires_at' => Carbon::now()->addMinutes(1),
        ]);

        $token->tokenable()->associate($user);
        $token->save();

        // add token into request so header Authorization will be available in FormRequest
        $this->withToken($token->getKey().'|'.$generated_token);

        return $token;
    }

    protected function setFakeNow(string $date = null): Carbon
    {
        $this->now = $date ? Carbon::parse($date) : Carbon::now();

        Carbon::setTestNow($this->now);

        return $this->now;
    }
}
