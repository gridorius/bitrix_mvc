<?php

namespace GRA;

class Request{
    public static $server;
    public static $post;
    public static $get;
    public static $urlVar;
    private static $simple;

    private function __construct(){
        $this->server = static::$server;
        $this->post = static::$post;
        $this->get = static::$get;
        $this->urVar = static::$urlVar;
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

    public static function init($urlVariables){
        static::$server = $_SERVER;
        static::$post = $_POST;
        static::$get = $_GET;
        static::$urlVar = $urlVariables;

        $className = static::class;
        static::$simple = new $className();
    }
}