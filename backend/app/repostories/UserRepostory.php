<?php
namespace app\repostories;

use app\exceptions\InternalServerException;
use app\models\UserModel;
use app\repostories\AuthRepostory;
use libs\Response;

class UserRepostory
{
    public static function find($id)
    {
        $model = new UserModel();
        $user = $model->getById($id);
        return $user;
    }

    public static function update($id, $data)
    {
        $model = new UserModel();
        $model->update($id, $data);
        $user = $model->getById($id);
        return $user;
    }

    public static function updateToken($id)
    {
        $user = static::find($id);
        $record = AuthRepostory::login($user);
        return $record;
    }

    public static function resetPassword($id, $password, $newpassword)
    {
        $model = new UserModel();
        $user = $model->getById($id, false);

        try {
            if(password_verify($password, $user['password'])) {
                $model->update($id, ['password' => password_hash($newpassword, PASSWORD_DEFAULT)]);
            }
            return [
                'code' => 200,
                'message' => 'Đổi mật khẩu thành công.'
            ];
        } catch (\Exception $ex) {
            Response::json(200, [
                'code' => 422,
                'message' => 'Mật khẩu không chính xác.'
            ]);
            exit();
        }
    }
}