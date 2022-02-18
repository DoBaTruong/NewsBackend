<?php
namespace app\exceptions;

use Exception;

class AuthException extends Exception
{
    protected $message = 'You don\'t have permission to access this page';
    protected $code = 403;

    public function __construct() {
        return parent::__construct($this->message, $this->code);
    }  
}