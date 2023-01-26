<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

class EmailNotVerifiedException extends ConflictException
{
    protected $message = 'Email is not verified.';
}
