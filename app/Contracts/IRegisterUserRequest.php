<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Descriptors\UserType;

interface IRegisterUserRequest
{
    public function getName(): string;

    public function getEmail(): string;

    public function getPlainPassword(): string;

    public function getType(): UserType;
}
