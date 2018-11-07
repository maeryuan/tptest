<?php

namespace app\shopping\controller;
use think\Controller;

class Image extends Controller{
    public function listImage(){
        return $this->fetch();
    }
}

