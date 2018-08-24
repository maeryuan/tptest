<?php

namespace app\mess\controller;
use think\Controller;
use app\mess\model\MesInfo;
class SelMess extends Controller{
    public function index(){
        $mes = new MesInfo();
          #获取留言信息
        // 查询状态为1的用户数据 并且每页显示2条数据
        $list = MesInfo::where(NULL)->paginate(2);
        // 获取分页显示
        $page = $list->render();
        $this->assign('res', $list);
        $this->assign('page', $page);
        return $this->fetch('selmess');
    }
}

