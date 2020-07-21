<?php

namespace GRA;

class Model{
    protected static $queryDelimiters = [
        '=', '!', '<=', '>=', '<', '>'
    ];
    protected static $iblockClass = CIBlock;
    protected static $selectMethodName = 'GetList';
    protected $terms = [];
    protected $termsRaw = [];
    protected $select = [];
    protected $pagination = false;
    public $result;
    public $arResult = [];
    public $iblockId;
    public $sectionId;
    public $id;
    public $parametres;
    public $properties;
    public $additional = [];
    public $additionalProps = [];

    public function __construct($parametres = []){
        $this->iblockId = $parametres['IBLOCK_ID'];
        $this->sectionId = $parametres['IBLOCK_SECTION_ID'];
        $this->id = $parametres['ID'];
        $this->parametres = $parametres;
    }


    // ('ID=21', 'name=stas|kik|as|dfd', 'age>20')

    public function add(){
        $parameters = $this->additional + ['IBLOCK_ID' => $this->iblockId,
                'IBLOCK_SECTION_ID' => $this->sectionId,
                'PROPERTY_VALUES' => $this->additionalProps];

        $m = (new static::$iblockClass)->Add($parameters);
    }

    public function where(...$terms){
        $this->terms += $terms;
        return $this;
    }

    public function whereRaw($array){
        $this->termsRaw += $array;
        return $this;
    }

    public function clearWhere(...$terms){
        $this->terms = $terms;
        return $this;
    }

    public function select(...$items){
        $this->select += $items;
        return $this;
    }

    public function pagination($pageNum, $pageSize){
        $this->pagination = ["nPageSize"=>$pageSize, "iNumPage"=> $pageNum];
        return $this;
    }

    public function clearSelect(...$items){
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

    public function __set($name, $value){
        if($this->properties[$name])
            $this->properties[$name] = $value;
        elseif($this->parametres[$name])
            $this->parametres[$name] = $value;

        if(preg_match("/PROPERTY_/", $name))
            $this->additionalProps[preg_replace('/PROPERTY_(.+?)$/', "$1", $name)] = $value;
        else
            $this->additional[$name] = $value;
    }

    public function getPrepareTerms(){
        $arFilter = [];
        foreach ($this->terms as $term){
            preg_replace_callback("/^(.+?)(". implode('|', static::$queryDelimiters) .")(.+?)$/", function($matches) use (&$arFilter){
                $arFilter[$matches[2].$matches[1]] = $matches['3'];
            }, $term);
        }

        $arFilter += $this->termsRaw;

        return $arFilter;
    }

    public function getCount(){
        return $this->result->result->num_rows;
    }

    public function get(){
        $arFilter = $this->getPrepareTerms();
        $this->getResult($arFilter);
//        $this->clearSelect()->clearWhere();
        return $this;
    }

    public function getResult($arFilter){
        $this->result = (new static::$iblockClass)->{static::$selectMethodName}([], $arFilter, false, $this->pagination, $this->select);
    }

    public function getElementArray(){
        $className = static::class;
        $arResult = [];
        while($item = $this->result->GetNextElement())
            $arResult[] = $item;

        return $arResult;
    }

    public function getArray(){
        $className = static::class;
        $this->arResult = [];
        while($item = $this->result->GetNext())
            $this->arResult[] = new $className($item);

        return $this->arResult;
    }
}