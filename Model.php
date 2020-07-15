<?php

namespace GRA;

class Model{
    protected static $queryDelimiters = [
        '=', '!', '<=', '>=', '<', '>'
    ];
    protected static $iblockClass = CIBlock;
    protected static $selectMethodName = 'GetList';
    protected $terms = [];
    protected $select = [];
    public $result;
    public $arResult = [];
    public $iblockId;
    public $id;
    public $parametres;
    public $properties;

    public function __construct($parametres = []){
        $this->iblockId = $parametres['IBLOCK_ID'];
        $this->id = $parametres['ID'];
        $this->parametres = $parametres;
    }


    // ('ID=21', 'name=stas', 'age>20')
    public function where(...$terms){
        $this->terms += $terms;
        return $this;
    }

    public function select(...$items){
        $this->select = $items;
        return $this;
    }

    public function __get($name)
    {
        if($this->properties[$name])
            return $this->properties[$name];
        elseif($this->parametres[$name])
            return $this->parametres[$name];
    }

    public function getPrepareTerms(){
        $arFilter = [];
        foreach ($this->terms as $term){
            preg_replace_callback("/^(.+?)(". implode('|', static::$queryDelimiters) .")(.+?)$/", function($matches) use (&$arFilter){
                $arFilter[$matches[2].$matches[1]] = $matches['3'];
            }, $term);
        }

        return $arFilter;
    }

    public function get(){
        $arFilter = $this->getPrepareTerms();
        $this->result = (new static::$iblockClass)->{static::$selectMethodName}([], $arFilter, false, false, $this->select);
        return $this;
    }

    public function getArray(){
        $className = static::class;
        $this->arResult = [];
        while($item = $this->result->GetNext())
            $this->arResult[] = new $className($item);

        return $this->arResult;
    }
}