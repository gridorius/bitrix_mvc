<?php

namespace GRA;

class AtomicCssView extends View{
    public $cssContent = "";
    public $matches = [];

    protected $classReplacement = [
        "/w-(.+?)(\s|\")/" => "width: $1px",
        "/h-(.+?)(\s|\")/" => "height: $1px",
        "/pt-(.+?)(\s|\")/" => "padding-top: $1px",
        "/pr-(.+?)(\s|\")/" => "padding-right: $1px",
        "/pb-(.+?)(\s|\")/" => "padding-bottom: $1px",
        "/pl-(.+?)(\s|\")/" => "padding-left: $1px",
        "/mt-(.+?)(\s|\")/" => "margin-top: $1px",
        "/mr-(.+?)(\s|\")/" => "margin-right: $1px",
        "/mb-(.+?)(\s|\")/" => "margin-bottom: $1px",
        "/ml-(.+?)(\s|\")/" => "margin-left: $1px",
        "/fs-(.+?)(\s|\")/" => "font-size: $1px",
        "/c-(.+?)(\s|\")/" => "color: $1",
    ];

    public function findMatches(){

    }

    public function getCss(){

    }
}