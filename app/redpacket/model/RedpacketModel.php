<?php

namespace app\redpacket\model;

use think\Model;
use think\Paginator;

class RedpacketModel extends Model {
   
    /**
     * 获取红包模板的分页数据
     * @param  $where
     * @param int $num
     * @return 分页对象
     */
    public function getAllRedpacket($where=null,$num=5){
        return $this->where($where)->paginate($num);
    }
   
    /**
     * 根据where条件查询一条数据
     * @param type $where
     * @return type obj
     */
    public function findOne($where){
        return $this->where($where)->find();
    }
    /**
     * 根据where条件更新数据
     * @param array $data
     * @param array $where
     * @return int 受影响的条数
     */
    public function updateInfo($data,$where){
        return $this->where($where)->update($data);
    }
    /**
     * 删除红包数据
     * @param array $where
     * @return type
     */
    public function delRedpacket($where){
        return $this->where($where)->delete();
    }
    /**
     * 插入数据，并返回自增id
     * @param type $data
     * @return type
     */
    public function insertRedpacket($data){
        return $this->insertGetId($data);
    }
    /**
     * 查询所有的红包名和id
     * @param type $where
     * @return array
     */
    public function getRedpacketInfo($where=null){
        return $this->where($where)->field("rd_name,redpacket_model_id")->select();
    }
}
