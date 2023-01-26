<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Descriptors\UserType;

interface ILoggedUser extends IIdentifiedUser
{
    public function getType(): UserType;

    public function isA(UserType $type): bool;
}
