<?php

namespace app\snake\controller;
use think\Controller;
use app\snake\model\NodeModel;
class Index extends Controller{
    public function index()
    {
        $username = "lili";
        session('role',$username);
        $rolename = 'admin';
        $this->assign('username',$username);
        $this->assign('rolename',$rolename);
        // 获取权限菜单
//        $node = new NodeModel();
//        $this->assign([
//            'menu' => $node->getMenu(session('rule'))
//        ]);

        return $this->fetch('/index');
    }
}

