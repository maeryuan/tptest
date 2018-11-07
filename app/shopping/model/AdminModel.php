<?php
namespace app\shopping\model;
use think\Model;

class AdminModel extends Model{
    
    protected $table = "admin";
    
    public function getOne(){
        return $this->get(1);
    }
}

