<?php

namespace GRA;

// get components from input url
// url /home/{variable name}

class Router{
    static $routes = [];
    public $path;
    public $variables;
    public $controller;
    public $method = 'get';
    public $requestMethod;

    public function __construct($path, $variables, $controller, $method){
        $this->path = $path;
        $this->variables = $variables;
        $this->controller = $controller;
        $this->method = $method;
    }

    public function setRequestMethod($method){
        $this->requestMethod = $method;
    }

    public static function check($url){
        foreach(static::$routes as $route)
            if(preg_match($route->path, $url) && $route->requestMethod == Request::$method){
                $route->fill($url);
                return $route;
            }

        return static::getEmpty();
    }

    public static function getEmpty(){
        return new Router(null, null, null, null);
    }

    public static function addRoute($route, $controller){
        $controller = explode(':', $controller);
        preg_match_all("/\{(.+?)\}/", $route, $names);
        $parsed_route = '/' . preg_replace_callback("/\{(.+?)\}|\//", 'static::replace', $route) . '\/?$/';
        return static::$routes[] = new Router($parsed_route, $names[1], $controller[0], $controller[1]);
    }

    public static function get($route, $controller){
        static::addRoute($route, $controller)->setRequestMethod('get');
    }

    public static function post($route, $controller){
        static::addRoute($route, $controller)->setRequestMethod('post');
    }

    public static function pull($route, $controller){
        static::addRoute($route, $controller)->setRequestMethod('pull');
    }

    public static function delete($route, $controller){
        static::addRoute($route, $controller)->setRequestMethod('delete');
    }

    public function fill($url){
        preg_match($this->path, $url, $result);
        array_shift($result);
        $names = array_combine($this->variables, $result);
        foreach ($names as $key => $value)
            $this->{$key} = $value;
        $this->variables = $names;
    }

    private static function replace($replacement){
        if($replacement[0] == '/') return '\/';
        else return '(.+?)';
    }
}