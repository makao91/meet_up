<?php

namespace App\Exceptions\Http;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends AppException
{
    protected $message = 'Not found.';
    protected $code = Response::HTTP_NOT_FOUND;
}
