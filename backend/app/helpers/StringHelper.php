<?php
namespace app\helpers;

class StringHelper
{
    public static function slug($string)
    {
        $string =  mb_strtolower($string);
        $string = html_entity_decode($string);
        $unicode = [
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ'
        ];

        foreach ($unicode as $english => $vietnamese) {
            $string = preg_replace('/('.$vietnamese.')/i', $english, $string);
        }

        $string = preg_replace('/[^A-Za-z0-9\s]/', '', $string);

        return str_replace(' ', '-', $string);
    }
}
