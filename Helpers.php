<?php

namespace GRA;

function view($view, $controller){
    return new View($view, $controller);
}

function response($data){
    return new Response($data);
}