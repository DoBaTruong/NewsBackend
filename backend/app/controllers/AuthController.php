<?php
namespace app\controllers;

use app\repostories\AuthRepostory;
use libs\JWT;
use libs\Response;
use libs\Request;

class AuthController
{
    public function login()
    {
        $email = Request::get('email');
        $pass = Request::get('password');
        $hasUser = AuthRepostory::hasUser($email, $pass, false);

        $response = $hasUser ? AuthRepostory::login(AuthRepostory::hasUser($email, $pass)) : [
            'code' => 422,
            'message' => 'Đăng nhập thất bại.'
        ];
        Response::json(200, $response);
    }

    public function register()
    {
        $email = Request::get('email');
        $name = Request::get('name');
        $password = password_hash(Request::get('password'), PASSWORD_DEFAULT);
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'created_at' => date('Y-m-d H:i:s', time())
        ];

        $user = AuthRepostory::register($data);
        
        if($user) {
            $respone = [
                'code' => 200,
                'message' => 'Tạo tài khoản thành công.'
            ];
        } else {
            $respone = [
                'code' => 422,
                'message' => 'Đăng ký chưa thành công.'
            ];
        }

        Response::json(200, $respone);
    }

    public function logout()
    {
        $rftoken = Request::get('rftoken');
        $response = AuthRepostory::logout($rftoken) ? [
            'code' => 200,
            'message' => 'Đăng xuất thành công !'
        ] : [
            'code' => 500,
            'message' => 'Internal Server Error'
        ];
        Response::json(200, $response);
    }

    public function hasEmail()
    {
        $email = Request::get('email');
        $sign = Request::get('sign');
        $respone = [
            'hasEmail' => ($sign ? !AuthRepostory::hasEmail($email) : AuthRepostory::hasEmail($email)) ? true : false,
        ];
        Response::json(200, $respone);
    }

    public function refreshToken()
    {
        $refreshToken = Request::get('rftoken');

        $data = AuthRepostory::refreshToken($refreshToken);

        $token = JWT::encode($data['user'], $data['secret']);

        Response::json(200, [
            'token' => $token,
            'expiredIn' => $data['expired_at']
        ]);
    }
}