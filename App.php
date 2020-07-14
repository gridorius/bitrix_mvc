<?php

namespace GRA;

class App{
    public static $config;
    public static $url;
    private static $app;

    private function __construct(){

    }

    public static function init(){
        static::$app = new App();
        static::$url = $_SERVER['REQUEST_URI'];
        static::autoloadInit();
        static::loadConfig();
        static::route();
    }

    public static function route(){
        $route = Router::check(static::$url);
        $controller = new $route->controller();
        $response = $controller->{$route->method}();
        static::returnResponse($response);
    }

    public static function returnResponse($response){
        $response->show();
    }

    public static function get($name){
        return static::$config[$name];
    }

    public function __get($name)
    {
        return static::$config[$name];
    }

    private static function autoloadInit(){
        spl_autoload_register(function ($name) {
            list($vendor, $module) = explode('\\', $name);

            if (!empty($module))
                include $_SERVER['DOCUMENT_ROOT'] . '/App/' . implode('/', $module). '.php';
        });

    }

    private static function loadConfig(){
        $config_text = file_get_contents(__DIR__ . '/Config.json');
        static::$config = json_decode($config_text);
    }
}