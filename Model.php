<?php

namespace GRA;

class Model{
    private static $queryDelimiters = [
        '=', '!', '<=', '>=', '<', '>'
    ];
    protected static $iblockClass = CIBlock;
    protected static $selectMethodName = 'GetList';
    protected $terms = [];
    public $result;


    // ('ID=21', 'name=stas', 'age>20')
    public function where(...$terms){
        $this->terms += $terms;
        return $this;
    }

    public function get(){
        $arFilter = [];
        foreach ($this->terms as $term){
            preg_replace_callback("/^(.+?)(". implode('|', static::$queryDelimiters) .")(.+?)$/", function($matches) use (&$arFilter){
                $arFilter[$matches[2].$matches[1]] = $matches['3'];
            }, $term);
        }


        $this->result = (new static::$iblockClass)->{static::$selectMethodName}([], $arFilter);
        return $this->result;
    }
}