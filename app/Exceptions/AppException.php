<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * All Exceptions which are thrown by application should extend this class
 * Interface HttpExceptionInterface is used to generate valid response status code by Laravel.
 */
class AppException extends \RuntimeException implements HttpExceptionInterface
{
    protected $message = 'Application error occurred.';
    protected $code = Response::HTTP_EXPECTATION_FAILED;
    protected array $extra_data = [];
    protected array $headers = [];

    public function __construct(?string $message = null, \Throwable $previous = null)
    {
        $message = $message
            ? __($message)
            : __($this->message);

        parent::__construct($message, $this->code, $previous);
    }

    public function getExtraData(): array
    {
        return $this->extra_data;
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
