<?php
namespace app\middlewares;

use app\repostories\AuthRepostory;
use libs\JWT;
use libs\Response;

class CheckTokenMiddleware extends Middleware
{
    protected $next = true;

    public function action() {
        $token = $_SERVER['HTTP_X_ACCESS_TOKEN'];
        
        if($token !== 'undefined') {
            $rftoken = $_SERVER['HTTP_X_REFRESH_TOKEN'];
            $secret = AuthRepostory::getSecret($rftoken);
            $user = JWT::decode($token, $secret);
            AuthRepostory::isExpired($user->id);
        } else {
            Response::json(200, [
                'code' => 401,
                'message' => 'Token is expired.'
            ]);
            exit();
        }
        
        return $this->next;
    }
}