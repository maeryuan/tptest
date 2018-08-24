<?php
namespace app\admin\model;
use think\Model;
class Admin extends Model{
     /**
     * 查找一条数据
     * @param type $where
     * @return type
     */
    public function findOne($where){
        return $this->where($where)->select();
    }
}

