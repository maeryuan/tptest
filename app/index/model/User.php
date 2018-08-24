<?php

namespace app\index\model;

use think\Model;
class User extends Model{
    /**
     * 根据查询条件where查询一条数据
     * @param array $where
     * @return array
     */
    public function findOne($where){
       return $this->where($where)->select();
    }
    
    /**
     * 插入用户信息，返回自增id
     * @param array $data
     * @return int
     */
    public function insertUser($data){
        return $this->insertGetId($data);
    }
    
    public function updateInfo($data,$where){
        return $this->where($where)->update($data);
    }
}

