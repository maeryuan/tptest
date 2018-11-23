<?php

namespace app\snake\controller;
use think\Controller;
use app\snake\model\NodeModel;
class Index extends Controller{
    public function index()
    {       
     //获取菜单权限
        //实例化节点
        $node = new NodeModel();
        //根据node节点获取菜单
       $menu = $node->getMenu(session('rule'));
       $this->assign('menu',$menu);
       return $this->fetch('/index');
    }
    
    public function indexPage(){
        return $this->fetch('index');
    }
}

