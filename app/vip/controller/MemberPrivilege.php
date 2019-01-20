<?php

namespace app\vip\controller;

use think\Controller;
use app\vip\model\MemberRankModel;
class MemberPrivilege extends Controller {

    /**
     * 会员特权：配置特权
     * 
     */
    public function index() {

        return $this->fetch();
    }

    /**
     * 关联vip等级和特权页面
     */
    public function relevancePrivilege() {
        #获取vip等级id rank_id
        $rankId = input('param.rank_id');
        #查询vip等级相关信息
        $rankInfo = objToArray(MemberRankModel::where(['id'=>$rankId])->find());
        #赋值到view
        $this->assign('rankId', $rankId);
        $this->assign('rankName',$rankInfo['rank_name']);
        return $this->fetch();
    }
    
    /**
     * 处理给vip会员配置对应特权
     */
    public function doRelevance() {
        #获取数据
        $arr = input('param.');
        var_dump($arr);
    }

    public function getPrivilege() {
        #获取user_id，rank_id，根据rank_id配置特权
        $user_id = 10000288;
        $rankId = 1;
        switch ($rankId) {
            case'1':
                $privilegeName = 'birthday';
        }
    }
    /**
     * 特权福利操作
     */
    public function Privilege(){
        $act = input('param.act');
        switch ($act){
            case 'add':
                return $this->fetch('add_privilege');
        }
    }
    /**
     * 添加特权
     */
    public function addPrivilege(){
        $arr = input('param.');
        var_dump($arr);
    }
}
