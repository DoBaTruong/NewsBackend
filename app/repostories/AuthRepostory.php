<?php
namespace app\repostories;

use app\exceptions\InternalServerException;
use app\models\PersonalAccessToken;
use app\models\UserModel;
use libs\JWT;
use libs\Response;

class AuthRepostory
{
    public static function hasUser($email, $hash, $hidden = true) {
        $model = new UserModel();
        $model->where(['email', $email]);
        $user = $model->first($hidden);
        $status = true;

        if(!$hidden) {
            $status = false;

            if($user && count($user) > 0) {
                $status = password_verify($hash, $user['password']);
            }
        }

        return $status ? $user : null;
    }

    public static function login(array $user, $remember = false) 
    {
        $rftoken = bin2hex(openssl_random_pseudo_bytes(64));
        $secret = bin2hex(openssl_random_pseudo_bytes(100));
        $secret = preg_replace('/[^a-zA-Z]/', '', $secret);
        $token = JWT::encode($user, $secret);
        $model = new PersonalAccessToken();
        $model->where('user_id', $user['id']);
        $check = $model->first();
        $expire_date = date('Y-m-d H:i:s', time() + 86400);
        $data = [
            'user_id' => $user['id'],
            'token' => $rftoken,
            'secret' => $secret,
            'expired_at' => $expire_date,
            'last_used_at' => date('Y-m-d H:i:s', time())
        ];

        if($check) {
            $status = $model->update($check['id'], $data);
        } else {
            $status = $model->create($data);
        }
        $response = $status ? ['code' => 200, 'expireIn' => $expire_date, 'token' => $token, 'rftoken' => $rftoken] : 
        ['code' => 500, 'message' => 'Đăng nhập thất bại.'];
        return $response;
    }

    public static function register($data)
    {
        $model = new UserModel();
        $user = $model->create($data);
        return $user;
    }

    public static function hasEmail($email)
    {
        $model = new UserModel();
        $model->where(['email', $email]);
        return $model->first();
    }

    public static function isExpired($userId)
    {
        $model = new PersonalAccessToken();
        $model->where(['user_id', $userId]);
        $token = $model->first();
        if($token) {
            $time = strtotime($token['expired_at']);
            if($time < time()) {
                Response::json(200, [
                    'code' => 401,
                    'message' => 'Token is expired.'
                ]);
                exit();
            } else {
                return true;
            }
        } else {
            $error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage()
            ]);
            exit();
        }
    }

    public static function getSecret($rftoken)
    {
        $model = new PersonalAccessToken();
        $model->where('token', $rftoken);
        $record = $model->first();
        if($record) {
            return $record['secret'];
        }

        return '';
    }

    public static function logout($token)
    {
        $model = new PersonalAccessToken();
        $model->where(['token', $token]);
        $record = $model->first();
        (bool) $status = true;
        if($record) {
            $status = $model->delete($record['id']);
        }
        return $status;
    }

    public static function refreshToken($token)
    {
        $model = new PersonalAccessToken();
        $model->where(['token', $token]);
        $record = $model->first();
        $secret = bin2hex(openssl_random_pseudo_bytes(50));
        if($record) {
            $date = date('Y-m-d H:i:s', time() + 86400);
            $model->update($record['id'], [
                'secret' => $secret,
                'expired_at' => $date
            ]);

            $userModel = new UserModel();
            $userModel->where(['id', $record['user_id']]);
            $user = $userModel->first();

            return [
                'expired_at' => $date,
                'secret' => $secret,
                'user' => $user
            ];
        } else {
            Response::json(200, [
                'code' => 403,
                'message' => 'You don\'t have permission to access this page'
            ]);
            exit();
        }
    }
}