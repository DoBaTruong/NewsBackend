<?php
namespace app\middlewares;

class AuthMiddleware extends Middleware
{    
    protected $next = true;

    public function action()
    {
        echo 'Bạn đang ở auth middleware';
        return $this->next;
    }
}