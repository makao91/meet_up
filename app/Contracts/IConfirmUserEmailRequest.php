<?php

declare(strict_types=1);

namespace App\Contracts;

interface IConfirmUserEmailRequest
{
    public function getId(): int;

    public function getToken(): string;
}
