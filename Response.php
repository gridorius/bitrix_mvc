<?php

namespace GRA;

class Response{
    public $headers;
    public $content;
    public $json = false;

    public function __construct($content){
        if(!is_string($content) && !is_int($content))
            $this->json($content);
        else
            $this->content = $content;
    }

    public function isJSon(){
        return $this->json;
    }

    public function json($object){
        $this->addHeader('Content-Type: application/json');
        $this->content = json_encode($object);
    }

    public function addHeader($header){
        $this->headers[] = $header;
    }

    public function get(){
        return $this->content;
    }

    public function show(){
        foreach($this->headers as $header)
            header($header);
        echo $this->content;
    }
}