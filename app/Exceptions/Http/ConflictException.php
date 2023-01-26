<?php

namespace App\Exceptions\Http;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class ConflictException extends AppException
{
    protected $message = 'Conflict.';
    protected $code = Response::HTTP_CONFLICT;
}
