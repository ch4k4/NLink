<?php
/**
 * Not Found Exception
 */

class NotFoundException extends AppException
{
    protected string $errorCode = 'NOT_FOUND';
    protected int $httpStatus = 404;
    
    public function __construct(
        string $message = "Resource not found",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
