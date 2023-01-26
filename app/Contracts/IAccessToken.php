<?php

declare(strict_types=1);

namespace App\Contracts;

use Carbon\Carbon;

interface IAccessToken
{
    public function getId(): int;

    public function getUserId(): int;

    public function getExpiresAt(): Carbon;

    public function getPlainToken(): string;
}
