<?php
namespace app\exceptions;

use Exception;

class InternalServerException extends Exception
{
    protected $message = 'Internal Server Error';
    protected $code = 500;

    public function __construct() {
        return parent::__construct($this->message, $this->code);
    }
}