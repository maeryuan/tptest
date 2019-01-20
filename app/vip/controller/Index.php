<?php

namespace app\vip\controller;

use think\Controller;
use app\vip\model\MemberModel;
use app\vip\model\MemberRankModel;
use app\vip\model\MemberPrivilegeModel;
use app\vip\model\MemberFlowModel;
use think\cache\driver\Redis;

class Index extends Controller {
    #1.检测用户是否登陆
    #2.获取用户的user_id
    #3.调用member接口

    public function index() {
        #1.检测用户状态是否登陆，如果登陆，获取用户userId
        $userId = '10000288';
        #缓存的key值
        $keyInfo = 'vip' . "$userId";

        $redis = new Redis();

        #判断是否缓存，没有缓存则查询数据库，并进行缓存
        if (!$redis->get($keyInfo)) {
            #2.根据userId查询用户的rank_id
            $userRes = MemberModel::where(['user_id' => $userId])->find();
            if (!$userRes) {
                return json(msg(404, '', '资源不存在'));
            }
            $userInfo = objToArray($userRes);
            $rankId = $userInfo['rank_id'];
            #3.根据rank_id，获取rank_name，privilege信息
            $rankRes = MemberRankModel::where(['id' => $rankId])->find();
            if (!$rankRes) {
                return json(msg(404, '', '资源不存在'));
            }
            #会员等级
            $rankInfo = objToArray($rankRes);
            $rankName = $rankInfo['rank_name'];

            #会员等级对应的特权福利信息
            $privilegeRes = MemberPrivilegeModel::where(['rank_id' => $rankId])->select();
            if (!$privilegeRes) {
                return json(msg(404, '', '资源不存在'));
            }
            $privilegeInfo = (objToArray($privilegeRes));

            foreach ($privilegeInfo as $key => $val) {
                $privilegeInfo[$key]['content'] = objToArray(json_decode($val['content']));
            }
            $memberFlowRes = MemberFlowModel::where(['rank_id' => $rankId])->find();
            if (!$memberFlowRes) {
                return json(msg(404, '', '资源不存在'));
            }
            $memberFlowInfo = objToArray($memberFlowRes);
            $arr = $privilegeInfo;
            $arr['rank_name'] = $rankName;
            $arr['rank_id'] = $rankId;
            $arr['max_points'] = $rankInfo['max_points'];
            $arr['available_rank_points'] = $memberFlowInfo['available_rank_points'];
            #将数据写入缓存，缓存时间1小时，key值为$user_id加字段
            $redis->set($keyInfo, json_encode($arr,true), 3600 * 24 * 30);
            return json(msg(200, $arr, 'vip会员等级特权信息from mysql！'));
        } else {
            $data = json_decode($redis->get($keyInfo),true);
            return json(msg(200, $data, 'vip会员等级特权信息from redis！'));
        }
    }

    /**
     * 获取vip对应的特权信息
     * @return array
     */
    public function vipToPrivilege() {
        #rank_id为12345
        $rows = array();
        for ($i = 1; $i <= 5; $i++) {
            $res = MemberPrivilegeModel::where(['rank_id' => $i])->select();
            $rows[$i] = objToArray($res);
        }
        return $rows;
    }

    public function main() {
        return $this->fetch('/main');
    }
}
