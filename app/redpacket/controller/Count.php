<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\RedpacketRecord;
class Count extends Controller{
    public function index(){
        #统计红包名，及发放个数
       // SELECT redpacket_model_id,  count(redpacket_model_id) FROM redpacket_record GROUP BY redpacket_model_id 
       //$model->field('count(username) num,username')->group('username')->order('num desc')->limit('3');         
                $redpacketRecord =new RedpacketRecord;
                $res= $redpacketRecord
                        ->query('SELECT redpacket_model_id,  count(redpacket_model_id) FROM redpacket_record GROUP BY redpacket_model_id');
                     
               
        return $this->fetch();
    }
}
