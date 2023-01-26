<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

class InvalidConfirmEmailTokenException extends BadRequestException
{
    protected $message = 'Invalid email verification token.';
}
