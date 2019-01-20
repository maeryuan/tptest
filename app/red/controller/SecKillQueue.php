<?php

namespace app\red\controller;

use think\Controller;
use think\cache\driver\Redis;

class SecKillQueue extends Controller {

    public $waitQueue = 'wait';
    public $skQueue = 'sk';

    /**
     * 创建队列
     * @param type $queueName 队列名
     * @param type $val 存入的的值
     */
    public function createQueue($queueName, $val) {
        $redis = new Redis();
        $redis->handler()->lpush($queueName, $val);
    }

    /**
     * 获取队列的长度
     * @param type $queueName
     * @return int/false
     */
    public function getQueueLen($queueName) {
        $redis = new Redis();
        return $redis->handler()->llen($queueName);
    }

    /**
     * 每次pop出一个单位,减库存
     * @param type $queueName
     */
    public function setDecQueueLlen($queueName) {
        $redis = new Redis();
        $result = $redis->handler()->lpop($queueName);
        if (!$result) {
            return false;
        } else {
            return $result;
        }
    }
    /**
     * 每次push进去一个单位，添加库存
     * @param type $queueName
     * @return boolean
     */
    public function setIncQueueLlen($queueName,$val=1) {
        $redis = new Redis();
        $result = $redis->handler()->lpush($queueName,$val);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
    
    public function delQueue($queueName){
         $redis = new Redis();
        $redis->handler()->del($queueName,1);
    }
}
