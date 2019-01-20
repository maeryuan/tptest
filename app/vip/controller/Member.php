<?php

namespace app\vip\controller;

use think\Controller;
use app\vip\model\MemberModel;
use app\vip\model\MemberFlowModel;
use app\vip\model\MemberPrivilegeModel;
use app\vip\model\MemberRankModel;

class Member extends Controller {
    #1.获取user_id，
    #2.member表，查询rank_id，
    #3.member_rank表，获取等级名rank_name,
    #4.member_privilege表获取用户特权privilege
    #5.将获得数据返回到前端展示

    public function index() {
        #1.从缓存中，获取user_id
        $userId = 10000288;
        #2.从表member中，查询rank_id
        $memberInfo = MemberModel::where(['user_id' => $userId])->field(['rank_id'])->find();
        if (!$memberInfo) {
            return json(msg(404, '', '数据不存在'));
        }
        $rankId = objToArray($memberInfo)['rank_id'];
        #3.从member_rank中，查询rank_name
        $rankInfo = MemberRankModel::where(['id' => $rankId])->field(['rank_name'])->find();
        if (!$rankInfo) {
            return json(msg(404, '', '数据不存在'));
        }
        $rankName = objToArray($rankInfo)['rank_name'];
        #4.从member_privilege中，查询privilege_name，content
        $privilegeInfo = MemberPrivilegeModel::where(['rank_id' => $rankId])->field(['privilege_name', 'content'])->find();
        if (!$privilegeInfo) {
            return json(msg(404, '', '数据不存在'));
        }
        $vipInfo = objToArray($privilegeInfo);
        $vipInfo['rank_name'] = $rankName;
        $vipInfo['user_id'] = $userId;
        $this->assign('vipInfo',$vipInfo);
        session('user_id',$userId);
        return $this->fetch();
        
        
//        return msg(200, $vipInfo, '');
    }

}
