<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\RedpacketModel;
use app\admin\model\RedpacketRecord;
class Recordrepacket extends Controller{
  #可发送红包列表  
    public function index(){
        $res = RedpacketModel::where('is_delete', '=', 0)->select();
        
        $this->assign('res', $res);
        return $this->fetch('record_list');
    }
    #编辑发送红包
    public function record(){
        $redpacket_model_id = input('param.redpacket_model_id');
        //dump($redpacket_model_id);
        $res = RedpacketModel::get($redpacket_model_id);
        //dump($res);
        $this->assign('res', $res);
        return $this->fetch('redpacket_record');
    }
    #操作，生成二维码，并发送
    public function doRecord(){
        return "发送红包成功！";
    }
    
    #查看已经发送的红包
    public function oldRecordList(){
//        $res = RedpacketRecord::where('is_delete', '=', 0)->select();
//        
//        $this->assign('res', $res);
        return $this->fetch('oldrecord_list');
    }
}
