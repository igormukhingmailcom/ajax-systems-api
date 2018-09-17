<?php

namespace Mukhin\AjaxSystemsApi\Exception;

use Throwable;

class AuthenticationException extends \RuntimeException implements Exception
{
    /**
     * AuthenticationException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "An authentication exception occurred.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
