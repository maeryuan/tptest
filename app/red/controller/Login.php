<?php

namespace app\red\controller;

use think\Controller;
use think\Session;
use app\red\model\AdminModel;

class Login extends Controller {

    //登录界面
    public function index() {

        //验证是否登录成功
        if (Session::has('userName')) {
            $this->redirect('index/index');
        }
        return $this->fetch('login');
    }

    /**
     * 登陆操作
     */
    public function doLogin() {
        #判断提交表单方式
        if (request()->ispost()) {
            #获取表单提交数据
            $username = input('post.username');
            $password = input('post.password');
//            $code = input('post.code');
            $data = [
                'username' => $username,
                'password' => $password];
            #根据username,password查询数据库
            $user = AdminModel::get([
                        'username' => $username,
                        'password' => md5($password)
            ]);
            #判断查询结果
            if (!$user) {
                return json(msg(-2, '', '账号或密码错误'));
            }
            #开启session（username）user_id                       
            session('userName', $user->username);
            session('userId', $user->id);
            #跳转主页面
            return $this->redirect("Index/index");
//            return json(msg(200, url('Index/index'), '登录成功！'));
        }
    }

    //退出操作
    public function doOut() {
        echo '退出后台管理';
        session('userName', null);
        session('userId',null);
        return $this->redirect('Index/index');

    }

}
