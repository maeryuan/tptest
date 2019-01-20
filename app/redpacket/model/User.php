<?php

namespace app\admin\model;

use think\Model;
class User extends Model
{
     /**
     * 查询一条数据
     * @param array $where
     * @return obj返回一条数据
     */
    public function findOne($where) {
        return $this->where($where)->find();
    }
}