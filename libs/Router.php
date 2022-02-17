<?php

use app\exceptions\NotFoundException;
use libs\Registry;
use libs\Response;

class Router
{
    public static array $routes;
    public $notFoundError = false;

    public static function get($path, $handler, $middlewares = [])
    {
        self::addRouter('GET', $path, $handler, $middlewares);
    }    

    public static function post($path, $handler, $middlewares = [])
    {
        self::addRouter('POST', $path, $handler, $middlewares);
    }    

    public static function put($path, $handler, $middlewares = [])
    {
        self::addRouter('PUT', $path, $handler, $middlewares);
    }    

    public static function patch($path, $handler, $middlewaress = [])
    {
        self::addRouter('GET', $path, $handler, $middlewaress);
    }    

    public static function delete($path, $handler, $middlewares = [])
    {
        self::addRouter('DELETE', $path, $handler, $middlewares);
    }

    public static function options($path, $handler, $middlewares = [])
    {
        self::addRouter('OPTIONS', $path, $handler, $middlewares);
    }    

    public static function all($path, $handler, $middlewares = [])
    {
        self::addRouter('GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD', $path, $handler, $middlewares);
    }    

    public static function addRouter($methods, $path, $handler, $middlewares)
    {
        $path = '/'.trim($path, '/');

        foreach (explode('|', $methods) as $method) {
            self::$routes[] = [$method, $path, $handler, $middlewares];
        }
    }

    public function map()
    {
        $numHandled = 0;
        $requestUri = '/' . trim($_SERVER['PATH_INFO'] ?? '/', '/');
        $requestMethod = $this->getRequestMethod();
        
        foreach( self::$routes as $route) {
            list($method, $path, $handler, $middlewares) = $route;
            if(strpos($method,$requestMethod) !== FALSE) {
                $pattern = $this->rewriteUri($path);
                $isMatches = $this->isMatches($requestUri, $pattern);
                if($isMatches && count(explode('/', trim($path, '/'))) === count(explode('/', trim($requestUri, '/')))) {
                    foreach ($middlewares as $middleware) {
                        $middlewareClass = 'app\middlewares\\'.ucfirst(trim($middleware)).'Middleware';
                        if(class_exists($middlewareClass)) {
                            Registry::getInstance()->middleware = $middlewareClass;
                            $middlewareObj = new $middlewareClass;
                            $middlewareObj->action();
                        }
                    }
                    $isParams = $this->isParams($path);
                    if(count($isParams)) {
                        $params = $this->getParams($path, $requestUri);
                        
                        if(is_callable($handler)) {
                            call_user_func_array($handler, $params);
                        } elseif (is_string($handler)) {
                            $this->complieRoute($handler, $params);
                        }
                    } else {
                        if(is_callable($handler)) {
                            $handler();
                        } elseif (is_string($handler)) {
                            $this->complieRoute($handler, []);
                        }
                    }
                    ++$numHandled;
                }
            } 
        }

        if($numHandled === 0) {
            $this->notFoundError();
        }
    }

    public function run()
    {
        return $this->map();
    }

    public function notFoundError()
    {
        $error = new NotFoundException();
        Response::json(200, [
            'code' => $error->getCode(),
            'message' => $error->getMessage()
        ]);
    }

    public function getParams($path, $uri)
    {
        $pathArr = explode('/', trim($path, '/'));
        $uriArr = explode('/', trim($uri, '/'));

        $params = [];

        foreach($pathArr as $k => &$p) {
            if(boolval(preg_match_all('/^\{(.*?)\}$/', $p, $matches))) {
                $params[] = $uriArr[$k];
            }
        }

        return $params;
    }

    public function isParams($path)
    {
        preg_match_all('/\{(\w*?)\}/', $path, $matches);

        return isset($matches[1]) ? array_fill_keys($matches[1], null) : [];
    }

    public function isMatches($uri, $pattern)
    {
        return boolval(preg_match_all('/^'.$pattern.'$/', $uri, $matches));
    }

    public function rewriteUri($path)
    {
        $path = preg_replace('/\{(.*?)\}/', '(.*?)', $path);
        $arr = explode('/', $path);
        foreach($arr as &$a) {
            if(strcmp('(.*?)', $a) !== 0 && !empty($a)) {
                $a = '('.$a.')';
            }
        }
        return implode('\/', $arr);
    }

    public function complieRoute($handler, $params = [])
    {
        if(count(explode('@', $handler)) == 2) {
            list($className, $methodName) = explode('@', $handler);

            if(class_exists($className)) {
                Registry::getInstance()->controller = $className;
    
                $controller = new $className;
    
                if(method_exists($controller, $methodName)) {
                    Registry::getInstance()->method = $methodName;
                    call_user_func_array([$controller, $methodName], $params);
                } else {
                    // Hanle error code
                }
            } else {
                // Hanle error code
            }
        }
    }
    
    public function getRequestHeaders()
    {
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();

            if ($headers !== false) {
                return $headers;
            }
        }

        foreach ($_SERVER as $name => $value) {
            if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                $headers[str_replace(array(' ', 'Http'), array('-', 'HTTP'), ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headers;
    }

    public function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            ob_start();
            $method = 'GET';
        }

        elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $headers = $this->getRequestHeaders();
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], array('PUT', 'DELETE', 'PATCH'))) {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }

        return $method;
    }
}