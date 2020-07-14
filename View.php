<?php

namespace GRA;

class Viev{
    private $sourcesPath;
    private $cachedPath;
    public $name;
    public $data;

    public function __construct($name, $data){
        $this->sourcesPath = App::$config->templates->source;
        $this->cachedPath = App::$config->templates->cached;
        $this->name = $name;
        $this->data = $data;
    }

    private function prepareSourcePath($string){
        return $this->sourcesPath . preg_replace("/\./", $string) . '.so.php';
    }

    private function prepareCahcedPath($string){
        return $this->cachedPath. $string . '.php';
    }

    private function updateCache($source, $cache){
        $so_text = file_get_contents($source);
        $so_text = preg_replace("/\{\{(.+?)\}\}/g", "<?php $1 ?>");
        file_put_contents($cache, $so_text);
    }

    private function checkLastCached(){
        $source = $this->prepareSourcePath($this->name);
        $cache = $this->prepareCahcedPath($this->name);

        if(fileatime($source) > fileatime($cache))
            $this->updateCache($source, $cache);
    }

    public function load(){
        $cache = $this->prepareCahcedPath($this->name);
        $this->checkLastCached();
        ob_start();
        include $cache;
        $html = ob_get_clean();
        ob_end_clean();
        return $html;
    }
}