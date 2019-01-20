<?php
//redis 加锁
namespace app\red\controller;

use think\Controller;
use think\cache\driver\Redis;

class Lock extends Controller {

    /**
     * 
     * @param type $key redis的key
     * @param type $timeout 超时时间
     * @param type $expire 过期时间
     * @return boolean
     */
    public function loopLock($key, $timeout = 8, $expire = 3) {
        $redis = new Redis();
        
        #超时
        $timeout = time() + $timeout;
        #循环，获取lock
        while (true) {
            #获取锁
            $result = $redis->get($key);
            #key为空，当前没有锁
            if (empty($result)) {
                #获取到锁,设置key对应的value，以及过期时间expire
                $redis->set($key, time() + $expire, $expire);
                return true;
            }
            #查看当前的key在redis的剩余生存时间
            $ttl = $redis->get($key) - time();
            #TTL 小于 0 表示 key 上没有设置生存时间,抢夺锁
            if ($ttl < 0) {
                usleep(5000);
                continue;
            }

            #超时退出
            if (time() > $timeout) {
                break;
            }
            usleep(5000);  #休眠0.005秒 降低抢锁频率，缓解redis压力
        }
        return false;
    }

    /**
     * 释放锁
     * @param String $key 锁标识
     * @return Boolean
     */
    public function unlock($key) {
        $redis = new Redis();
        return $redis->del($key);
    }

}
