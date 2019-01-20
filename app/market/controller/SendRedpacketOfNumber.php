<?php

namespace app\market\controller;

use think\Controller;

class SendRedpacketOfNumber extends Controller {

    /**
     * 根据用户输入的手机号，发送红包
     * @return type
     */
    public function sendRedpacketOfPhone() {

        #1.获取用户输入的手机号，userId，红包模板id
        $phoneNumber = input('param.phoneNumber');
        $userId = input('param.user_id');
        $redpacketModelId = input('param.redpacket_model_id');
        #红包的过期时间
        $expireTime = 7;
        #接收时间
        $createTime = time();
        #2.生成唯一的32位id(拼接用户id,手机号,红包模板id，以及当时的微妙时间戳)  
        $redpacketCode = md5($userId . $phoneNumber . $redpacketModelId . uniqid());
        #3.根据$redpacketCode红包唯一id查看redis缓存中是否存在该id
        $redis = new Redis();
        #如果存在，则表示该手机号码已经使用过
        if ($redis->get($redpacketCode)) {
            return json(msg(-1, $redis->get($redpacketCode), '该手机号已经领取红包，不能再领取了！'));
        } else {
            #缓存不存在，查询数据库
            $res = RedpacketRecordModel::where(['redpacket_code' => $redpacketCode])->find();
            #如果该红包唯一id存在，则表示改手机号已经领取过
            if ($res) {
                $data = objToArray($res);
                $redis->set($redpacketCode, json_encode($data, true), 3600 * 24 * 30);
                return json(msg(-1, $data, '该手机号已经领取红包，不能再领取了！'));
            }
        }
        #查询过后都不存在，则该手机号码可以使用
        $arr = [
            'redpacket_model_id' => $redpacketModelId,
            'redpacket_code' => $redpacketCode,
            'user_id' => $userId,
            'expire_time' => $expireTime,
            'create_time' => $createTime
        ];
        #添加 数据库操作
        $redpacketRecord = new RedpacketRecordModel();
        $redpacketRecord->data($arr);
        $res = $redpacketRecord->save();
        if (!$res) {
            return json(msg(403, '', '操作延时，请刷新后再操作！'));
        }
        #添加数据库之后，将该条数据从数据库中查询出来，存入redis缓存中
        $result = $redpacketRecord->get($redpacketRecord->redpacket_record_id);
        $data = objToArray($result);
        $redis->set($redpacketCode, json_encode($data, true), 3600 * 24 * 30);
        return json(msg(200, $data, '发放成功！'));
    }

}
