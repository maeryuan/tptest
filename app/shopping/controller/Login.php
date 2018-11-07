<?php

namespace app\shopping\controller;

use think\Controller;
use think\Session;
use app\shopping\model\AdminModel;

class Login extends Controller {

    //登录界面
    public function index() {

        //验证是否登录成功
        if (Session::has('userinfo')) {
            $this->redirect('index/index');
        }
        return $this->fetch('login');
    }

    //登陆操作
    public function doLogin() {
        
        if (!request()->isPost()) {
            $this->redirect('index/index');
        }
        $name = input('post.adminname');
        $passwd = input('post.password');

        if (!$name || !$passwd) {
            return array('status' => 0, 'msg' => '用户名和密码不能为空');
        }

        $info = AdminModel::where(['username' => $name])->find();

        $md5_passwd = md5($passwd);

        if (!$info || $md5_passwd != $info['password']) {
            exit(json_encode(array('status' => 0, 'msg' => '用户名或密码错误，请重新输入')));
        }
        //登入成功，存入session
        Session::set('userinfo', $info['username']);
        return $this->fetch('index/index');
    }

    //退出操作
    public function doOut() {
        echo '退出后台管理';
        session('userinfo', null);
        return $this->fetch('login');
    }

}
