<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

class InvalidLoginCredentialsException extends UnauthorizedException
{
    protected $message = 'Invalid email or password provided.';
}
