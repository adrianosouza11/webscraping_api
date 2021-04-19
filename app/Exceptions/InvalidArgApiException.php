<?php

namespace App\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidArgApiException extends InvalidArgumentException
{
    public function __construct(string $message = '',int $code = 400, Throwable $previous = null)
    {
        $text = strlen($message) > 0 ? $message : "Invalid parameters";

        parent::__construct($text, $code, $previous);
    }

    public function __toString()
    {
        parent::__toString();
    }

}