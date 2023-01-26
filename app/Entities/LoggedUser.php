<?php

declare(strict_types=1);

namespace App\Entities;

use App\Contracts\ILoggedUser;
use App\Models\Descriptors\UserType;

class LoggedUser implements ILoggedUser
{
    protected int $id;

    public function __construct(
        int $id,
    ) {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): UserType
    {
        return UserType::USER;
    }

    public function isA(UserType $type): bool
    {
        return $this->getType() === $type;
    }
}
