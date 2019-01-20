<?php

namespace  app\admin\model;

use think\Model;

class YiNode extends Model{
    

     public function getAllNode($where=null){
        return $this->where($where)->select();
    }
}

