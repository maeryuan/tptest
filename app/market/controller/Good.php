<?php

namespace app\market\controller;

use think\Controller;
use app\market\model\GoodModel;
use think\cache\driver\Redis;

class Good extends Controller {

    //获取商品的库存，并使用redis缓存库存

    public function setRedisGoodStore($goodId = 1) {
        #从mysql中获取good库存
        $res = GoodModel::where(['good_id' => $goodId, 'is_delete' => 0])->find();
        if (!$res) {
            return json(msg(-200, '', '商品已经下架'));
        }
        $goodStore = $res->nums;
        $goodName = $res->name;
        #在使用redis存入库存之前，先删除你要设定的库存队列
        $redis = new Redis();
        $redis->handler()->del($goodName . $goodId);
        #使用for循环将good库存，存入redis缓存
        for ($i = 1; $i <= $goodStore; $i++) {
            $redis->handler()->lpush($goodName . $goodId, 1);
        }
//        echo $redis->handler()->llen($goodName.$goodId);
    }
    /**
     * 根据商品的redis库存名，添加库存
     * @param type $queueName
     */
    public function setIncGoodStore($queueName){
        $redis = new Redis();
         $redis->handler()->lpush($queueName, 1);
    }
    /**
     * 根据商品redis库存名，减少库存
     * @param type $queueName
     */
    public function setDecGoodStore($queueName){
        $redis = new Redis();
         $redis->handler()->lp($queueName);
    }
    
    /**
     * 根据goodId获取商品的redis库存名，商品不存在返回false
     * @param type $goodId
     * @return string
     */
    public function getQueueName($goodId){
         $res = GoodModel::where(['good_id' => $goodId, 'is_delete' => 0])->find();
         if(!$res){
             return false;
         }
         $goodName = $res->name;
         $queueName = $goodName.$goodId;
         return $queueName;
    }
    /**
     * 
     * @param type $goodId
     * @return type obj
     */
    public function getGoodInfo($goodId=1){
        $res = GoodModel::where(['good_id' => $goodId, 'is_delete' => 0])->find();
         if(!$res){
             return json(msg(-200, '', '数据不存在'));
         }
         return $res;
    }
    
    /**
     * 获取redis队列的长度
     * @param type $queueName
     * @return type
     */
    public function getQueueLen($queueName){
        $redis = new Redis();
        return $redis->handler()->llen($queueName);
    }
}
