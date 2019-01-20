<?php

namespace app\test\controller;
use think\Controller;
use think\cache\driver\Redis;

class Index extends Controller{
    public function index(){
        
    }
    
    public function testRedis(){
        #1.首先，new一个redis实例
        $redis = new Redis();
        #2.然后，进行连接(tp5框架中可以在config中配置缓存redis)
//        $redis->connect('127.0.0.1',7200);
        #进行redis的类型操作：string操作
        
        #赋值set($name,$value,$expire);取值get();删除rm()
        #首先，确保操作的key值是空的，使用rm()删除
        $redis->rm('string1');
        #然后，我们可以使用redis的set方法给key赋值
        $redis->set('string1', 'value1');
        #之后，使用redis的get方法获取key对应的值
        $val = $redis->get('string1');
        #打印出来，查看
        var_dump($val);
        
        /**
         * 自增inc($name,$step)
         */       
        $redis->set('string2', 4);
        $redis->inc('string2',5);
        
    }
}

