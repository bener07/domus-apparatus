<?php

namespace App\Exceptions;

use Exception;

class UserException extends Exception
{
    protected $userMessage;
    protected $userCode;

    public function __construct($message = "Houve um problema com o utilizador", $code = 406){
        $this->userMessage = $message;
        $this->userCode = $code;

        parent::__construct($message, $code);
    }

    public function report(){
        \Log::error("UserException: {$this->userMessage}");
    }

    public function render($request){
        return response()->json(["error"=>$this->userMessage,"message"=> $this->getMessage()], $this->getCode());
    }
}
