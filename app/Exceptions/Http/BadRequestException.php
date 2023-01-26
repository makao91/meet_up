<?php

namespace App\Exceptions\Http;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends AppException
{
    protected $message = 'Bad request.';
    protected $code = Response::HTTP_BAD_REQUEST;
}
