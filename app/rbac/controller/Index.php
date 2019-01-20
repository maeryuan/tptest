<?php

namespace app\rbac\controller;

use think\Controller;

class Index extends Controller {
    public function index(){
        return $this->fetch();
    }

    public function main() {
        $act = input('param.act');
        switch ($act) {
            case 'one':
                return $this->fetch('one');
            case 'two':
                return $this->fetch('two');
            case 'three':
                return $this->fetch('three');
            case 'four':
                return $this->fetch('four');
            case 'five':
                return $this->fetch('five');
        }
    }

}
