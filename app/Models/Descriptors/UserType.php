<?php

declare(strict_types=1);

namespace App\Models\Descriptors;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'Descriptors\UserType',
    description: 'User type',
    type: 'string',
)]
enum UserType: string
{
    case USER = 'USER';
}
