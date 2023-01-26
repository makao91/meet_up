<?php

declare(strict_types=1);

namespace App\Contracts;

interface IIdentifiedUser
{
    public function getId(): int;
}
