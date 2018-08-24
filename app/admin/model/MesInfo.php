<?php

namespace   app\admin\model;
use think\Model;
class MesInfo extends Model{
    /**
     * 插入数据到表中，并返回自增id
     * @param type $data
     * @return type
     */
    public function insertInfo($data){
       return $this->insertGetId($data);
    }
    /**
     * 查找一条数据
     * @param type $where
     * @return type
     */
    public function findOne($where){
        return $this->where($where)->select();
    }
    /**
     * 删除数据
     * @param type $where
     * @return type
     */
    public function delInfo($where){
        return $this->where($where)->delete();
    }
}

