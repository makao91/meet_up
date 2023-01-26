<?php

declare(strict_types=1);

namespace App\Entities;

use App\Contracts\IAccessToken;
use App\Contracts\ILoginResponse;

class LoginResponse implements ILoginResponse
{
    public function __construct(
        private readonly int $user_id,
        private readonly IAccessToken $token
    ) {
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getToken(): IAccessToken
    {
        return $this->token;
    }
}
