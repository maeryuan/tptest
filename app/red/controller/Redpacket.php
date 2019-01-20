<?php

namespace app\red\controller;

use think\Controller;
use app\red\model\RedpacketModel;
use app\red\model\RedpacketRecordModel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use app\red\controller\Lock;

class Redpacket extends Controller {

    /**
     * 根据url传递的act，跳转不同的页面
     * @return 
     */
    public function index() {
        $act = input('param.act');
        if ($act == 'addRedpacketModel') {
            return $this->fetch('add_redpacket_Model');
        }
        if ($act == 'editRedpacketModel') {
            $id = input('get.id');
            if (empty($id)) {
                return json(msg('405', '', '编辑失败，请重新操作！'));
            }
            $res = RedpacketModel::where(['redpacket_model_id' => $id])->find();
            if (!$res) {
                return json(msg('401', '', '数据不存在！'));
            }
            $row = objToArray($res);
            $this->assign('row', $row);

            return $this->fetch('edit_redpacket_model');
        }
        if ($act == 'issueRedpacket') {
            $id = input('get.id');
            if (empty($id)) {
                return json(msg('405', '', '编辑失败，请重新操作！'));
            }
            $res = RedpacketModel::where(['redpacket_model_id' => $id])->find();
            if (!$res) {
                return json(msg('401', '', '数据不存在！'));
            }
            $row = objToArray($res);
            $this->assign('row', $row);
            return $this->fetch('issue_redpacket');
        }
    }

    /**
     * 分页显示红包模板列表
     * @return 
     */
    public function listRedpacketModel() {
        //获取分页数据
        $list = RedpacketModel::where(['is_delete' => 0])->paginate(5);
        // 获取分页显示
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     * 编辑修改红包模板
     * @return string
     */
    public function editRedpacketModel() {
        $arr = input('param.');
        $redpacket_model_id = $arr['id'];

        unset($arr['id']);
        $arr['update_time'] = time();
        $res = RedpacketModel::where(['redpacket_model_id' => $redpacket_model_id])->update($arr);
        if (!$res) {
            return json(msg(401, '', '编辑操作失败！'));
        }
//        return $this->redirect("Redpacket/listRedpacketModel");
        return json(msg(200, url('Redpacket/listRedpacketModel'), '编辑红包模板，操作成功！'));
    }

    /**
     * 软删除红包模板
     * @return string
     */
    public function delRedpacketModel() {
        $redpacketModelId = input('param.id');
        #根据id获取实例对象
        $redpacketModel = RedpacketModel::get($redpacketModelId);
        #将is_delete属性值设置为1
        $redpacketModel->is_delete = 1;
        #保存
        $res = $redpacketModel->save();
        if (!$res) {
            return json(msg(401, '', '删除失败，请重新操作！'));
        }
//        return $this->redirect('Redpacket/listRedpacketModel');
        return json(msg(200, url('Redpacket/listRedpacketModel'), '删除红包模板，操作成功！'));
    }

    /**
     * 添加红包模板
     * @return string
     */
    public function addRedpacketModel() {
        $arr = input('param.');
        $arr['create_time'] = time();
        $redpacketModel = new RedpacketModel();
        $redpacketModel->data($arr);
        $res = $redpacketModel->save();
        if (!$res) {
            return json(msg(401, '', '添加失败，请重新操作！'));
        }
//        return $this->redirect('Redpacket/listRedpacketModel');
        return json(msg(200, url('Redpacket/listRedpacketModel'), '添加红包模板成功！'));
    }

    /**
     * 测试使用红包页面
     * @return type
     */
    public function testUseRedpacket() {
        $redpacketCode = 'e823f6563d1cbf668eacd9bc97ca8c35';
        $this->assign('redpacket_code', $redpacketCode);
        return $this->fetch('use_redpacket');
    }

    /**
     * 使用红包，处理
     * 当用户点击红包，使用时，将红包的模板id和用户id传递过来，根据id更新红包状态
     */
    public function useRedpacket() {
        #1.获取用户使用的红包id和用户的id
        $redpacketCode = input('param.redpacket_code');
        $redpacketRecord = new RedpacketRecordModel();
        $res = $redpacketRecord->where(['redpacket_code' => $redpacketCode])
                ->update(['use_status' => 1, 'update_time' => time()]);
        if (!$res) {
            return json(msg(402, '', '操作超时，请重新操作！'));
        }
        return json(msg(200, $redpacketCode, '已使用'));
    }

    /**
     * 载入到输入手机号的页面
     * @return type
     */
    public function phoneSendRedpacket() {
        return $this->fetch('phone_send_redpacket');
    }

    /**
     * redisLock 来处理用户手机号领取红包
     */
    public function doSendRedpacketOfPhone() {
        #1.获取用户输入的手机号，userId，红包模板id
        $phoneNumber = input('param.phoneNumber');
        $userId = input('param.user_id');
        $redpacketModelId = input('param.redpacket_model_id');
        $arr = ['phoneNumber' => $phoneNumber, 'user_id' => $userId, 'redpacket_model_id' => $redpacketModelId];
        #2.redis加锁,实例化lock
        $Lock = new Lock();
        #拼接一个唯一key值
        $key = $phoneNumber . time();
        #判断，是否获取锁
        if ($Lock->loopLock($key)) {
            $this->send($arr);            
        }else{
            return json(msg(-1, '', '操作超时'));
        } 
    }

    public function send($data) {
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
        $msgBody = json_encode($data);
        #8.生成消息
        $msg = new AMQPMessage($msgBody, ['content_type' => 'text/plain', 'delivery_mode' => 2]); //生成消息
        $channel->queue_bind($queueName, $exchangeName, $routingKey);
        #9.发送消息到交换机
        $channel->basic_publish($msg, $exchangeName, $routingKey); //推送消息到某个交换机

        $channel->close();
        $conn->close();
    }

}
