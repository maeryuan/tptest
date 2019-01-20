<?php

namespace app\admin\model;

use think\Model;

class RedpacketRecord extends Model{
    public function groupRecord($where=null){
        return $this->where($where)->field('redpacket_model_id')->count('redpacket_model_id')->group('redpacket_model_id')->select();
    }
}

