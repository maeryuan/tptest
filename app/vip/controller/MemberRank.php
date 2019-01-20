<?php

namespace  app\vip\controller;
use think\Controller;
use app\vip\model\MemberRankModel;
class MemberRank extends Controller{
    /**
     * 会员等级 12345分别的成长值要求多少
     * 成长值计算，vip投资（一个计算公式）
     * 是否升级vip等级
     * 
     */
    
    
    /**
     * 获取所有会员vip等级相关信息，赋值到view层
     * @return type
     */
    
    public function index(){
        
        return $this->fetch();
    }
    
    /**
     * 1.获取user_id
     * 2.根据user_id，rank_id，获取用户当前等级和成长值
     * 3.获取变更后的成长值，判断用户是否升级
     * 4.获取当前vip等级
     */
    public function rankName($userId = 10000288){
        
    }
    /**
     * 根据user_id，当前操作（投资或者提现）计算变更成长值
     */
    public function changeRankPoints(){
        #年化投资额 = 单笔投资金额*投资天数/360
        #成长值 = 年化投资额/(100*2)
        #1.获取投资信息（投资额和投资天数）
        $investment = 10000;
        $invsetDays = 30;
        #2.计算成长值
        $changeRankPoints = $investment * $invsetDays / (360*100*2);
        return $changeRankPoints;
    }
    /**
     * 配置，不同等级vip拥有的不同特权
     * 1.获取rank_id，rank_name
     * 2.rank_id（12345）分别对应的特权
     */
    public function confPrivilege(){
        
    }
}

