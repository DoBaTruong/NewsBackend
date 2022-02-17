<?php
namespace app\controllers;

use app\helpers\ImageHelper;
use app\helpers\StringHelper;
use app\repostories\UserRepostory;
use libs\Request;
use libs\Response;

class UserController extends Controller
{
    public function update(int $id)
    {
        $field = Request::get('field');
        $value = Request::get('value');
        if($field === 'photo') {
            $filename = StringHelper::slug(Request::get('filename'));
            $value = htmlspecialchars(ImageHelper::save($value, 'img/avatar', $filename));
        }
        UserRepostory::update($id, [$field => $value]);

        $result = UserRepostory::updateToken($id);
        Response::json(200, [
            'code' => 200,
            'result' => $result
        ]);
    }

    public function resetPassword()
    {
        $password = Request::get('password');
        $newpassword = Request::get('newpassword');
        $id = Request::get('id');
        settype($id, 'integer');
        $status = UserRepostory::resetPassword($id, $password, $newpassword);
        Response::json(200, $status);
    }
}
