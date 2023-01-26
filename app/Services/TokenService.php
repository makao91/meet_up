<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use App\Entities\AccessToken;
use Illuminate\Support\Carbon;
use App\Contracts\IAccessToken;
use App\Models\PersonalAccessToken;
use Illuminate\Contracts\Config\Repository;

class TokenService
{
    public function __construct(
        private PersonalAccessToken $personal_access_token_model,
        private Repository $config
    ) {
    }

    public function createToken(User $user, array $abilities = ['*']): IAccessToken
    {
        $generated_token = Str::random(40);
        $expires_in = $this->config->get('sanctum.expiration');

        $token = $this->personal_access_token_model->newInstance([
            'name' => $this->generateTokenName($user->email),
            'token' => hash('sha256', $generated_token),
            'abilities' => $abilities,
            'expires_at' => Carbon::now()->addMinutes($expires_in),
        ]);
        $token->tokenable()->associate($user);
        $token->save();

        return new AccessToken($token, $generated_token);
    }

    private function generateTokenName(string $email): string
    {
        return $email.'-'.Carbon::now()->timestamp;
    }
}
