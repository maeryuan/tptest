<?php
//redis 计数器
namespace app\red\controller;
use think\Controller;
use Predis\Client;
class RedisCount extends Controller{
    #计数器名称
    public function getKeyName($v){
        return 'mycounter_'.$v;
    }
    #获取客户端
    public function getRedisClient(){
        return new \app\home\Controller\Client([
            'host'=>'127.0.0.1',
            'port'=>'6379'
        ]);
    }
    public function wirteLog($msg,$v){
        $log = $msg.PHP_EOL;
        file_put_contents('log/$v.log', $log,FILE_APPEND);
    }
    
    public function v(){
        $amountLimit = 100;
        $keyName = getKeyName('v');
        $redis = getRedisClient();
        $incrAmount =1;
        if(!$redis->exists($keyName)){
            $redis->set($keyName,95);
        }
        $currAmount = $redis->get($keyName);
        if($currAmount + $incrAmount >$amountLimit){
            writeLog('Bad luck','v');
            return;
        }
        writeLog('Good Luck','vi');
    }
} 

