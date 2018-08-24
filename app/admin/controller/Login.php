<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin;

class Login extends Controller {

    public function index() {
        return $this->fetch('login');
    }

    /**
     * 登陆操作
     * @return type
     */
    public function login() {
        if (request()->ispost()) {
            #获取表单信息
            $username = input('post.username');
            $password = input('post.password');
            #判断登陆
            $user = new Admin();
            $result = $user->findOne(['username' => $username, 'password' => md5($password)]);
            if (!$result) {
                echo '账号或密码错误！';
                exit;
            }
            foreach ($result as $va) {
                $userInfo = $va->toArray();
            }
            session('userName', $userInfo['username']);
            session('userId', $userInfo['id']);
            session('userStatus', 1);

            $this->redirect(url('Index/index'));
        }
    }

    public function loginOut() {
        session('userName', NULL);
        session('userId', NULL);
        session('userStatus', NULL);
        $this->redirect(url('Index/index'));
    }

}
