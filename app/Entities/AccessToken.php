<?php

declare(strict_types=1);

namespace App\Entities;

use Carbon\Carbon;
use App\Contracts\IAccessToken;
use App\Models\PersonalAccessToken;

class AccessToken implements IAccessToken
{
    public function __construct(
        private readonly PersonalAccessToken $personal_access_token,
        private readonly string $generated_token,
    ) {
    }

    public function getId(): int
    {
        return $this->personal_access_token->id;
    }

    public function getUserId(): int
    {
        return $this->personal_access_token->tokenable_id;
    }

    public function getExpiresAt(): Carbon
    {
        return $this->personal_access_token->expires_at;
    }

    public function getPlainToken(): string
    {
        return $this->getId().'|'.$this->generated_token;
    }
}
