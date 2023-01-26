<?php

declare(strict_types=1);

namespace App\Contracts;

interface ILoginResponse
{
    public function getUserId(): int;

    public function getToken(): IAccessToken;
}
