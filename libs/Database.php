<?php
namespace libs;

use PDO;
use libs\Registry;

class Database 
{
    protected $connect;
    protected $error;

    public function __construct()
    {
        $config = Registry::getInstance()->config['database'];
        $dbhost = $config['host'];
        $dbname = $config['name'];
        $dbuser = $config['user'];
        $dbpass = $config['password'];
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        
        try {
            $dsn = "mysql:host=$dbhost;dbname=$dbname;charset=utf8";
            $this->connect = new PDO($dsn, $dbuser, $dbpass, $options);
        } catch (\PDOException $e) {
			$this->error = $e->getMessage();
		}
    }    
}