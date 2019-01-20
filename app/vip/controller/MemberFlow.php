<?php

namespace app\vip\controller;

use think\Controller;
use app\vip\model\MemberFlowModel;
use app\vip\model\MemberRankModel;

class MemberFlow extends Controller {

    /**
     * 1.获取user_id，获取操作类型type，及相关的资金操作，从而根据公式计算成长值。
     * 2.从memberflow表，查询change_rank_points变更值
     * available_rank_points变更后的值，update_time更新时间，type投资类型
     * 3.查询vip等级名称rank_name
     */
    public function index() {
        #1.从url获取user_id
        $userId = session('user_id');
        #2.从memberflow中，查询查询change_rank_points变更值，
        #available_rank_points变更后的值，
        #update_time更新时间，
        
        $result = MemberFlowModel::where(['user_id' => $userId])
                ->field(['change_rank_points', 'available_rank_points', 'update_time', 'rank_id'])
                ->find();
        if (!$result) {
            return json(msg(404, '', '请求资源不存在'));
        }
        $flowInfo = objToArray($result);
        #3.查询vip等级名称rank_name
        $rankInfo = MemberRankModel::where(['id' => $flowInfo['rank_id']])->field(['rank_name'])->find();
        if (!$rankInfo) {
            return json(msg(404, '', '请求资源不存在'));
        }
        $flowInfo['rank_name'] = objToArray($rankInfo)['rank_name'];

        $this->assign('flowInfo', $flowInfo);
        return $this->fetch();
//        return msg(200, $flowInfo, '');
    }

}
