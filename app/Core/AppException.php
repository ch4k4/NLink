<?php
/**
 * Base Exception
 * 
 * Base exception class for the application
 */

class AppException extends Exception
{
    protected string $errorCode = 'APP_ERROR';
    protected int $httpStatus = 500;
    
    public function __construct(
        string $message = "Application Error",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
    
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
    
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}
