<?php

namespace app\test\controller;

//use think\Controller;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class TestP {

    public function index() {
        #1.配置 主机地址；端口号；用户名；密码；虚拟机；
        $conf = [
            'host' => '127.0.0.1',
            'port' => 5672,
            'user' => 'guest',
            'pwd' => 'guest',
            'vhost' => '/'
        ];

        #2.设置交换机名字exchangeName；队列名字queueName；路由route_key
        $exchangeName = 'test_p_wei_ex';
        $queueName = 'test_p_wei_q';
        $routingKey = 'test_p_wei';
        #3.连接mq；建立生产者与mq之间的连接
        $conn = new AMQPStreamConnection($conf['host'], $conf['port'], $conf['user'], $conf['pwd'], $conf['vhost']);
        #4.创建连接和通道channel,在已连接基础上建立生产者与mq之间的通道
        $channel = $conn->channel();
        #5.声明初始化交换机exchange_declare(echangeName,type,$passive,$durable,$auto_delete)被动，持久，自动删除 
        $channel->exchange_declare($exchangeName, 'direct', false, true, false); //声明初始化交换机
        #6.声明一条队列用于我们发送消息
        $channel->queue_declare($queueName, false, true, false, false); //声明初始化一条队列
        #7.创建消息体msgbody
        $msgBody = json_encode(["name" => "mey111", "age" => 30]);
        #8.生成消息
        $msg = new AMQPMessage($msgBody, ['content_type' => 'text/plain', 'delivery_mode' => 2]); //生成消息
        $channel->queue_bind($queueName, $exchangeName, $routingKey);
        #9.发送消息到交换机
        $channel->basic_publish($msg, $exchangeName, $routingKey); //推送消息到某个交换机
        
        $channel->close();
        $conn->close();
    }

}
