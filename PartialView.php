<?php

namespace GRA;

class PartialView extends View{
    public function show(){
        echo $this->get();
    }
}