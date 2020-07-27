<?php

namespace GRA;

use Bitrix\Main\Application;

class App{
    public static $config;
    public static $url;
    private static $app;
    private static $response;
    public static $rootPath;
    public static $bitrixApplication;
    public static $title = false;

    private function __construct(){

    }

    public static function init(){
        static::$app = new App();
        static::$url = $_SERVER['SCRIPT_URL'];
        static::$rootPath = $_SERVER['DOCUMENT_ROOT'];
        static::$bitrixApplication = Application::getInstance();
        static::autoloadInit();
        static::loadConfig();
        static::initRoutes();
    }

    public static function setTitle($title){
        static::$title = $title;
    }

    public function getTitle(){
        return static::$title;
    }

    public function hasTitle(){
        return static::$title ? true : false;
    }

    public function showTitle(){
        echo static::$title;
    }

    public static function preparePath($path){
        return $_SERVER['DOCUMENT_ROOT'] . "/" . $path;
    }

    private static function initRoutes(){
        $routesDirectory = static::preparePath(static::$config->routes);
        include_once $routesDirectory;
    }

    public static function route(){
        Request::init();
        $route = Router::check(static::$url);
        Request::setUrlVariables($route->variables);
        if(!$route->controller) {
            static::$response = new Response('');
            return;
        }
        $className = 'App\\' . $route->controller;
        $controller = new $className();
        $response = $controller->{$route->method}(Request::getSimple());

        if($response instanceof View)
            $response->load();

        static::$response = $response;
    }

    public static function getResponse(){
        self::route();
        return static::$response->get();
    }

    public static function returnResponse(){
        if(!static::$response)
            static::route();
        static::$response->show();
        if(static::$response->isJson())
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