<?php
require_once '../libs/Autoloader.php';

use libs\Registry;

class App
{
    private $router;
    
    function __construct($config)
    {
        new Autoloader();
        $this->router = new Router();
        Registry::getInstance()->config = $config;
    }

    public function run()
    {
        $this->router->run();
    }
}   