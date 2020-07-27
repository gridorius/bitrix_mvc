<?php

namespace GRA;

class Mail{
    public $event;
    public $site;
    public $templateId;
    public $fields = [];

    public function __construct($event, $templateId, $site = SITE_ID){
        $this->event = $event;
        $this->templateId = $templateId;
        $this->site = $site;
    }

    public function __set($name, $value)
    {
        $this->fields[$name] = $value;
    }

    public function set($name, $value){
        $this->__set($name, $value);
        return $this;
    }

    public function setArray($fields){
        $this->fields = $fields;
        return $this;
    }

    public function send(){
        \CEvent::Send($this->event, $this->site, $this->fields, 'N', $this->templateId);
    }
}