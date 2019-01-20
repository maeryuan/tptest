<?php

namespace app\red\controller;

use think\Controller;
use think\cache\driver\Redis;
use app\red\controller\SecKillQueue;
use app\red\controller\Lock;
use app\red\controller\SendInfo;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use think\Db;
class OrderSkill extends Controller {

//    public $store = 1000;
//    public $good_id = 1;
//    public $good_store_name = 'good_store';
//    public $price = 100;
    /**
     * 往redis的队列中添加库存（用于测试数据）
     */
    public function setAddRedis($store = 1000, $good_store_name = 'good_store') {
        $q = new SecKillQueue();
        #确保队列不存在，先执行删除
        $q->delQueue($good_store_name);
        #创建库存队列，添加库存
        for ($i = 0; $i < $store; $i++) {
            $q->setIncQueueLlen($good_store_name, 1);
        }

    }
    /**
     * 测试秒杀api
     */
    public function testSk(){
        for($i = 1;$i<=41;$i++){
            $this->sekillApi($i);
            
        }
    }

    /**
     * 秒杀请求接口
     */
    public function sekillApi($userId) {
//        $userId = rand(1, 41);
        $goodId = rand(1, 2);
        $price = rand(80, 1000);
        #获取当前的库存
        $q = new SecKillQueue();
        $good_store = $q->getQueueLen('good_store');
        if ($good_store <= 0) {
            return json(msg(200, '', '没有库存，都被抢晚了！'));
        }
        #还有库存，进入抢购，将userId存入秒杀队列
        #加锁
        $lock = new Lock();
        #拼接一个唯一key值
        $key = $userId . $goodId . time();
        if ($lock->loopLock($key)) {
            $q->setIncQueueLlen('sk_queue', $userId);
        } else {
            return json(msg(-1, '', '操作超时，请刷新！'));
        }

        #从秒杀队列中获取userId,将userId的值发送到rabbitmq消息队列中
        $send = new SendInfo();
        while ($q->getQueueLen('sk_queue') > 0) {
            $userId = $q->setDecQueueLlen('sk_queue');
            $data = [
                'user_id' => $userId,
                'good_id' => $goodId,
                'price'=>$price
            ];
            #发送消息到rabbitmq中
            $send->send($data);
        }
    }

    /**
     * 消费消息
     */
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
        $receiver = new self();
        $channel->basic_consume($queueName, $consumerTag, false, true, false, false, [ $receiver, 'callfunc']);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $conn->close();
    }

    /**
     * 回调函数处理消息
     * @param type $msg
     * @return type json
     */
    public function callfunc($msg) {

        #获取消息
        $row = json_decode($msg->body, true);
        $userId = $row['user_id'];
        $goodId = $row['good_id'];
        $price = $row['price'];
        $redis = new Redis();
        $num = $redis->handler()->llen('goods_store' . $goodId);

        #生成订单
        $order_no = $this->buildOrderNo($userId);
        $arr = [
            'user_id' => $userId,
            'good_id' => $goodId,
            'order_no' => $order_no,
            'price' => $price,
            'create_time' => time()
        ];

        // 启动事务
        Db::startTrans();
        try {
            // 更新good_store表的库存数目;
            $res = Db::table("good_store")->where(['good_id' => $goodId])->update(['num' => $num, 'update_time' => time()]);

            #将订单信息插入order_record表中
            Db::table('order_record')->insert($arr);
            // 提交事务
            Db::commit();
            $mess = json_encode(msg(200, $arr, '提交订单成功！'));
            echo $mess;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            echo'失败';
            echo $e->getMessage();
            $mess = json(msg(-2, '', $e->getMessage() . '操作超时，抢单失败'));
            echo $mess;
        }
        return $mess;
    }

    /**
     * 生成唯一订单号
     * @return type
     */
    public function buildOrderNo($userId) {
        return $userId.time().mt_rand(1000,9999);;
    }

}
