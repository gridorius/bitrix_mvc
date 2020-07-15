<?php

function view($view, $controller){
    return new GRA\View($view, $controller);
}

function response($data){
    return new GRA\Response($data);
}