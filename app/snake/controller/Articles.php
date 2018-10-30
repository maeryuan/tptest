<?php

namespace app\snake\controller;
use think\Controller;

class Articles extends Controller{
    
    public function index(){
        return $this->fetch();
    }
}

