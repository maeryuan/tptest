<?php

namespace app\market\controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use think\Controller;
use think\cache\driver\Redis;
use think\Db;
use app\market\controller\Email;
use app\market\model\UserMainModel;
class Receiver extends Controller {

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
        $mail  = new Email();
        $res = UserMainModel::get($userId);
        $userName = $res->real_name;
        $email = $res->email;
        // 启动事务
        Db::startTrans();
        try {
            // 更新good表的库存数目;
            Db::table("good")->where(['good_id' => $goodId])->update(['nums' => $num, 'update_time' => time()]);
            #将订单信息插入order_record表中
            Db::table('order_record')->insert($arr);
            // 提交事务
            Db::commit();
            $mess = json_encode(msg(200, $arr, '提交订单成功！'));
            echo $mess;
            //获取用户的邮箱地址，并发送邮件
            
            $mail->email('18401514128@163.com', '领红包', $email, $userName, '您有一个红包需要尽快领取', '1月1号--1月15号期间，为回馈vip客户，本公司将想vip客户发送价值100元的现金卷红包！');
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
        return $userId . time() . mt_rand(1000, 9999);
        ;
    }

}