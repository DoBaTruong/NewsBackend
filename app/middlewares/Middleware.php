<?php
namespace app\middlewares;

abstract class Middleware
{
    protected $next = true;
    abstract public function action();
}
?>