<?php

namespace app\test\controller;

use think\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use app\test\model\OrderModel;

class Point extends Controller {

    public function index() {
        $this->work();
        return $this->fetch('index');
    }

    public function work() {
//        set_time_limit(31);
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
        $consumerTag = 'consumer';
        #3.连接mq；建立生产者与mq之间的连接
        $conn = new AMQPStreamConnection($conf['host'], $conf['port'], $conf['user'], $conf['pwd'], $conf['vhost']);

        #4.创建通道channel,在已连接基础上建立生产者与mq之间的通道
        $channel = $conn->channel();
        #6.声明初始化队列
        $channel->queue_declare($queueName, false, true, false, false);
        #初始化交换器exchange
        $channel->exchange_declare($exchangeName, 'direct', false, true, false);
        #7.绑定队列和交换机,使用routingKey
        $channel->queue_bind($queueName, $exchangeName, $routingKey);
        #获取消息的过程
//        $callback = function ($msg) {
//            echo ' [x] ', $msg->body, "\n";
//           
//        };
        $receiver = new self();

        $channel->basic_consume($queueName, $consumerTag, false, true, false, false, [ $receiver, 'callfunc']);

        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $conn->close();
    }

    public function callfunc($msg) {
        echo ' [x] ', $msg->body, "\n";
        $arr = json_decode($msg->body, true);
        $res = OrderModel::where(['fp_order_id' => $arr['fp_order_id']])->find();
        $orderInfo = objToArray($res);
        echo'投资金额为：' . $orderInfo['amount'];
        echo'剩余金额为：' . $orderInfo['remain_amount'];
        echo'成长值增长：'.$this->changeRankPoints($orderInfo['amount']);
    }

    public function changeRankPoints($investment) {
        #年化投资额 = 单笔投资金额*投资天数/360
        #成长值 = 年化投资额/(100*2)
        #1.获取投资信息（投资额和投资天数）
        #2.计算成长值
        $invsetDays = 30;
        $changeRankPoints =ceil($investment * $invsetDays / (360 * 100 * 2));
        return $changeRankPoints;
    }

}
