<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

class EmailAlreadyExistsException extends ConflictException
{
    protected $message = 'Email already exists.';
}
