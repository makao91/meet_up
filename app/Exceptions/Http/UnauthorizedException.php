<?php

namespace App\Exceptions\Http;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends AppException
{
    protected $message = 'Unauthorized.';
    protected $code = Response::HTTP_UNAUTHORIZED;
}
