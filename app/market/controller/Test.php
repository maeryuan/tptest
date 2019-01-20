<?php

namespace app\market\controller;

use think\Controller;
use app\market\model\RedpacketRecordModel;

class Test extends Controller {

    public function index() {
        return $this->fetch('t');
    }

    public function getRedpacketRecord() {
//        $userId = input('param.userId');
    
//        $res = RedpacketRecordModel::where(['user_id'=>$userId])->find();
//       $data = objToArray($res);
//       return json(msg(200, $data, '红包记录'));
        $arr = input('param.');
        return json(msg(200, $arr, '返回的数据'));
    }

}
