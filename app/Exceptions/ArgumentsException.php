<?php

namespace App\Exceptions;

use Exception;

class ArgumentsException extends Exception
{
    protected $customMessage;
    protected $customCode;

    public function __construct($message = "Um ou mais argumentos não está correto", $code = 0)
    {
        $this->customMessage = $message;
        $this->customCode = $code;

        parent::__construct($message, $code);
    }

    public function report()
    {
        // Optional: Log the exception or perform custom reporting
        \Log::error("ArgumentsException: {$this->customMessage}");
    }

    public function render($request)
    {
        // Optional: Customize the response for the exception
        return response()->json([
            'error' => true,
            'message' => $this->customMessage,
            'code' => $this->customCode,
        ], 400);
    }
}
