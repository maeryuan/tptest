<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\RedpacketModel;

class Redpacketedit extends Controller {
    #进入红包模板

    public function index() {
      
        $res = RedpacketModel::where('is_delete', '=', 0)->select();
        
        $this->assign('res', $res);
        return $this->fetch('index');
    }

    #添加页面

    public function addRedpacket() {
        return $this->fetch();
    }

    #添加操作

    public function addInfo() {
        $data = input('post.');
        $redpacketModel = new RedpacketModel;
        $result = $redpacketModel->save($data);
        if ($result > 0) {
            $this->index();
        } else {
            return $this->error('操作失败');
        }
    }

    #更新页面

    public function updateRedpacket() {
        $redpacket_model_id = input('param.redpacket_model_id');
        //dump($redpacket_model_id);
        $res = RedpacketModel::get($redpacket_model_id);
        //dump($res);
        $this->assign('res', $res);
    
        return $this->fetch('updateRedpacket');
    }

    #更新操作

    public function updateInfo() {
        $redpacket_model_id = input('param.redpacket_model_id');               
        $data = input('post.');        
        $res = RedpacketModel::where("redpacket_model_id", "=", "$redpacket_model_id")
                ->update($data);
        
        if ($res == 1) {
           $this->success('更新成功', 'index');
        } else {
            return $this->error('操作失败');
        }
    }

    #删除操作

    public function delRedpacket() {
        $redpacket_model_id = input('param.redpacket_model_id');
        $result = RedpacketModel::where('redpacket_model_id', "eq", "$redpacket_model_id")
                ->update(['is_delete' => 1]);
        if ($result == 0) {
            return $this->error('操作失败');
        } else {
            $this->success('删除成功', 'index');
        }
    }

}
