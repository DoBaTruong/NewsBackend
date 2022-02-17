<?php
namespace app\helpers;

use app\exceptions\InternalServerException;
use libs\Response;

class ImageHelper
{
    public static function save($image, $path = '', $name = '')
    {
        try {
            $data = preg_replace('/(data:image\/)|(base64,)/', '', $image);
            list($extension, $base64) = explode(';', $data);
            $decode = base64_decode($base64);
            $filename = empty($name) ? time().'.'.$extension : $name.'.'.$extension;
            $pathNew = '../public/'.$path.'/';
            if(!file_exists($path)) {
                mkdir($pathNew, 0777, true);
            }
            file_put_contents($pathNew.$filename, $decode);
            return $path.'/'.$filename;
        } catch (InternalServerException $ex) {
            Response::json(200, [
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
            exit();
        }
    }

    public static function remove($path)
    {
        $dir = '../public/'.$path;
        if(file_exists($dir)) {
           unlink($dir);
        }
    }
}


