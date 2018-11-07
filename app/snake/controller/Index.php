<?php

namespace app\snake\controller;
use think\Controller;
use app\snake\model\NodeModel;
class Index extends Controller{
    public function index()
    {       
        // 获取权限菜单
        $node = new NodeModel();
        $arr = $node->getMenu(session('rule'));
//        var_dump($arr['userName']);exit;
        $this->assign([
            'menu' => $node->getMenu(session('rule'))
        ]);

        return $this->fetch('/index');
    } /**
     * 后台默认首页
     * @return mixed
     */
    public function indexPage() {
        return $this->fetch('index');
    }
    
}

