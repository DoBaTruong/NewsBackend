<?php

use app\exceptions\InternalServerException;
use libs\Response;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods,Access-Control-Allow-Origin, Access-Control-Allow-Credentials, X-Refresh-Token, X-Access-Token, Authorization, X-Requested-With");

require_once '../app/App.php';
$config = require_once '../config/app.php';
date_default_timezone_set($config['timezone']);
try {
    $app = new App($config);
    $app->run();
} catch (Exception $e) {
    $error = new InternalServerException();
    Response::json(200, [
        'code' => $error->getCode(),
        'message' => $error->getMessage(),
    ]);
}
