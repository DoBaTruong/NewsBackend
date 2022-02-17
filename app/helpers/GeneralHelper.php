<?php
function public_path() {
    $type = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $servername = $_SERVER['SERVER_NAME'];
    return $type.'://'.$servername.'/news/public/';
}