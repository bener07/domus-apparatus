<?php

namespace App\Exceptions;

use Exception;

class ProductException extends Exception
{
    protected $productMessage;
    protected $productCode;

    public function __construct($message="Houve com o processamento dos produtos", $code = 1){
        $this->productMessage = $message;
        $this->productCode = $code;
        parent::__construct($message, $code);
    }

    public function report(){
        \Log::error("ProductException: {$this->productMessage}");
    }

    public function render($request){
        return response()->json(["error"=>$this->productMessage,"message"=> $this->getMessage()], $this->getCode());
    }
}
