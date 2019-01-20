<?php

namespace app\vip\controller;

use think\Controller;
use app\vip\model\MemberFlowModel;
class Regist extends Controller {
    #一个注册页面

    public function index() {
        return $this->fetch();
    }

    public function doRegist() {
        #1.获取注册提交表单数据
        $arr = input('post.');
        #2.处理数据【用户名是否符合要求，密码是否符合要求】
        #3.插入数据库，获取新增userid
        $userId = 1008601;
        #4.初始化用户等级和成长值流水等
        $res = $this->UserInitializeSet($userId);
        var_dump($res);
    }

    public function UserInitializeSet($userId) {
        #设置用户等级初始数据
        #用户等级为1，
        #change_rank_points为0，
        #available_rank_points为0，
        #privilege_name为birthday
        #创建时间create_time为date('Y-m-d H:I:s',time())
        $relationId = time();
        $changeRankPoints = 0;
        $availabelRankPoints = 0;
        $rankId = 1;
        $creatTime = date('Y-m-d H:I:s', time());
        $updateTime = '0000-00-00 00:00:00';
        $type = 2;

        #在member_flow表添加数据
        $data = array(
            'user_id' => $userId,
            'relation_id' => $relationId,
            'change_rank_points' => $changeRankPoints,
            'available_rank_points' => $availabelRankPoints,
            'rank_id' => $rankId,
//            'is_upgrade'=>$isUpgrade,
            'create_time' => $creatTime,
            'update_time' => $updateTime,
            'type'=>$type
        );
        $res = MemberFlowModel::insert($data);
        if($res){
           return  json_encode(msg(200, '', '注册成功'));
        }
    }

}
