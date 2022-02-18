<?php
function public_path() {
    $type = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $servername = $_SERVER['SERVER_NAME'];
    return $type.'://'.$servername.'/news/public/';
}

function recursive($data, $id = 0, &$result = [])
{
    foreach ($data as $value) {
        if ($value['parent_id'] == $id) {
            array_push($result, $value['id']);
            recursive($data, $value['id'], $result);
        }
    }

    return $result;
}