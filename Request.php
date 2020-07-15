<?php

namespace GRA;

class Request{
    public static $server;
    public static $post;
    public static $get;
    public static $urlVar;
    public static $method;
    private static $simple;

    private function __construct(){
        $this->server = static::$server;
        $this->post = static::$post;
        $this->get = static::$get;
        $this->urVar = static::$urlVar;
        $this->method = static::$method;
    }

    public static function setUrlVariables($urlVariables){
        static::$urlVar = $urlVariables;
    }

    public static function getSimple(){
        return static::$simple;
    }

    public function __get($name)
    {
        if(static::$server[$name]) return static::$server[$name];
        elseif(static::$post[$name]) return static::$post[$name];
        elseif(static::$get[$name]) return static::$get[$name];
        elseif(static::$urlVar[$name]) return static::$urlVar[$name];
    }

    public static function init(){
        static::$server = $_SERVER;
        static::$post = $_POST;
        static::$get = $_GET;
        static::$method = strtolower($_SERVER['REQUEST_METHOD']);

        $className = static::class;
        static::$simple = new $className();
    }
}