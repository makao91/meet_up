<?php

declare(strict_types=1);

namespace App\Contracts;

interface ILoginRequest
{
    public function getEmail(): string;

    public function getPlainPassword(): string;
}
