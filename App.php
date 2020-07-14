<?php

namespace GRA;

class App{
    public static $config;
    public static $url;
    private static $app;
    private static $response;
    public static $rootPath;

    private function __construct(){

    }

    public static function init(){
        static::$app = new App();
        static::$url = $_SERVER['REQUEST_URI'];
        static::$rootPath = $_SERVER['DOCUMENT_ROOT'];
        static::autoloadInit();
        static::loadConfig();
        static::initRoutes();
        //static::route();
    }

    public static function preparePath($path){
        return $_SERVER['DOCUMENT_ROOT'] . "/" . $path;
    }

    private static function initRoutes(){
        $routesDirectory = static::preparePath(static::$config->routes);
        include_once $routesDirectory;
    }

    public static function route(){
        $route = Router::check(static::$url);
        if(!$route) return;
        $className = 'App\\' . $route->controller;
        $controller = new $className();
        $response = $controller->{$route->method}();
        static::$response = $response;
    }

    public static function getResponse(){
        self::route();
        return static::$response->get();
    }

    public static function returnResponse(){
        self::route();
        static::$response->show();
        exit();
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

            if (!empty($module)){
                include_once $_SERVER['DOCUMENT_ROOT'] . '/App/Controllers/' . $module . '.php';
                include_once $_SERVER['DOCUMENT_ROOT'] . '/App/Models/' . $module . '.php';
            }

        });

    }

    private static function loadConfig(){
        $config_text = file_get_contents(__DIR__ . '/Config.json');
        static::$config = json_decode($config_text);
    }
}