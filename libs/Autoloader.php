<?php
class Autoloader
{
    function __construct()
    {
        spl_autoload_register([$this, 'autoload']);
        $this->fileLoader();
    }

    private function autoload($class)
    {
        $class = preg_replace('/^\\$/', '/', $class);
        $class = explode('/', $class);
        $c = array_pop($class);
        $filePath = '../'.strtolower(implode('/', $class)).'/'.$c.'.php';
        if(file_exists($filePath)) {
            require_once $filePath;
        }
    }

    private function fileLoader()
    {
        foreach($this->getFileLoad() as $file)
        {
            require_once '../'.$file;
        }
    }

    private function getFileLoad() 
    {
        return [
            'libs/Router.php',
            'routes/route.php',
            'libs/Database.php',
            'app/helpers/GeneralHelper.php',
        ];
    }
}