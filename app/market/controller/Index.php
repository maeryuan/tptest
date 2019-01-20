<?php

namespace app\market\controller;

use think\Controller;
use think\cache\driver\Redis;

class Index extends Controller {

    //后台主页
    public function index() {
        //检测管理员是否登陆
        if (!session('userName')) {
            //未登录，跳转到登陆页面
            return $this->redirect('login/index');
        } else {
            return $this->fetch('index');
        }
    }

    public function main() {
        $version = 'Apache/2.4.9';
        $this->assign('apache_version', $version);
        return $this->fetch('/main');
    }

    /**
     * tp5框架 redis访问原始的redis方法，需要使用handler（）方法，通过获取handler对象来访问
     */
    public function test() {
        $redis = new Redis();
//        print_r($redis->handler()->ttl('hi'));
        #调用lock（）方法获取锁，成功true，失败false
        $res = $this->lock('hi');
        if ($res) {
            echo '加锁成功';
            //在这里执行需要加锁的操作
        } else {
            echo'加锁失败';
        }
    }

    /**
     * 获取锁
     * @param type $key 锁的标识
     * @param type $expire 生存时间/过期时间
     * @param type $timeout 超时时间
     * @return boolean true/false
     */
    public function lock($key, $expire = 3, $timeout = 8) {
        //过期时间3秒
        $timeout = $timeout + time(); //超时5秒：生存时间3秒过后，超过5秒，即为超时
        $redis = new Redis();
        #直接进行获取锁操作
        while (true) {
            $lock = $redis->get($key);
//            var_dump(empty($lock));exit;
            #判断所是否存在
            if (empty($lock)) {
                #不存在，设置锁,设置过期时间
                $redis->set($key, time() + $expire, $expire);
                #返回结果true，获取了锁
                return true;
            }
            #锁存在，判断是否过期
            #获取剩余生存时间
            $ttl = $redis->handler()->ttl($key);
            if ($ttl < 0) {
                #剩余生存时间小于0 ，说明是负数，-1表示没有设置生存时间，-2表示锁不存在
                #延迟5000微秒在执行，该锁的执行
                usleep(5000); //5000微秒
                continue;
            }
            #锁存在，判断是否超时
            if (time() > $timeout) {
                #超时，则直接退出循环
                break;
            }
            usleep(5000); //延迟5000微妙，降低抢锁的频率，缓解redis压力
        }
        #没有获取到锁，返回false
        return false;
    }

    /**
     * 释放锁
     * @param String $key 锁标识
     * @return Boolean
     */
    public function unlock($key) {
        $redis = new Redis();
        $redis->handler()->del($key);
    }

}
