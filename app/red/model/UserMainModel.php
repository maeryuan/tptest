<?php

namespace app\red\model;
use think\Model;

class UserMainModel extends Model{
    protected  $table = 'user_main';
    
      /**
     * 模拟用户,数据越小测试用户重复购买越大
     * @return int
     */
    static public function getUserId(){
        $uid = mt_rand(1000000,9999999);
        return $uid;
    }
}

