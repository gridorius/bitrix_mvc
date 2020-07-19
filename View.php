<?php

namespace GRA;

class View extends Response {
    protected static $replacement = [
        "/\{\{(.+?)\}\}/" => "<?= $1 ?>",
        "/@foreach\((.+?)\)/" => "<? foreach($1): ?>",
        "/@endforeach/" => "<? endforeach; ?>",
        "/@if\((.+?)\)/" => "<? if($1): ?>",
        "/@elseif\((.+?)\)/" => "<? elseif($1): ?>",
        "/@endif/" => "<? endif; ?>",
        "/@view\((.+?)\)/" => "partialView($1)->show()",
        "/@js\((.+?)\)/" => "<script src='$1'></script>",
        "/@css\((.+?)\)/" => "<link href='$1'></link>"
    ];
    private $sourcesPath;
    private $cachedPath;
    public $name;
    public $data;

    public function __construct($name, $data){
        parent::__construct();
        $this->sourcesPath = App::$config->templates->source;
        $this->cachedPath = App::$config->templates->cached;
        $this->name = $name;
        $this->data = $data;
    }

    private function prepareSourcePath($string){
        return App::$rootPath . $this->sourcesPath . preg_replace("/\./", '/', $string) . '.so.php';
    }

    private function prepareCahcedPath($string){
        return App::$rootPath . $this->cachedPath. $string . '.php';
    }

    private function updateCache($source, $cache){
        $so_text = file_get_contents($source);
        $so_text = preg_replace(array_keys(static::$replacement), array_values(static::$replacement), $so_text);
        file_put_contents($cache, $so_text);
    }

    private function checkLastCached(){
        $source = $this->prepareSourcePath($this->name);
        $cache = $this->prepareCahcedPath($this->name);

        if(fileatime($source) > fileatime($cache) || App::$config->DevelopMode)
            $this->updateCache($source, $cache);
    }

    public function load(){
        $cache = $this->prepareCahcedPath($this->name);
        $this->checkLastCached();
        extract($this->data);
        ob_start();
        include $cache;
        $html = ob_get_contents();
        ob_end_clean();

        $this->setContent($html);
    }

    public function get(){
        $this->load();
        return parent::get();
    }

    public function show(){
        $this->load();
        parent::show();
    }
}