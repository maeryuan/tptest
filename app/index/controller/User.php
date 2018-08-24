<?php

namespace app\index\controller;

use think\Controller;
use app\index\Model\User as userModel;

class User extends Controller {

    public function index() {
        $user = new userModel();
        $result = $user->findOne(['id' => 2]);
    }

    /**
     * 注册操作
     * @return type
     */
    public function doReg() {
        if (request()->ispost()) {
            $username = input('post.username');
            $password = input('post.password');
            $email = input('post.email');
            $data = [
                'username' => $username,
                'password' => $password,
                'email' => $email
            ];
            #验证器，助手函数
            $validate = validate('UserValidate');
            #验证数据
            if (!$validate->check($data)) {
                return json(msg(-1, '', $validate->getError()));
            }

            #验证用户名是否存在
            $user = new userModel();
            if ($user->findOne(['username' => $username])) {
                return json(msg(-2, '', '用户名已存在'));
            }

            #验证邮箱地址是否存在
            if ($user->findOne(['email' => $email])) {
                return json(msg(-2, '', '邮箱已存在'));
            }
            #将password加密
            $data['password'] = md5($password);
            #将用户注册信息插入数据库，并返回自增id
            $id = $user->insertUser($data);
            if (!$id) {
                return json(msg(-3, '', '注册失败，请重新注册！'));
            }
            #将用户名，id写入session中
            session('userName', $username);
            session('userId', $id);
//            return json(msg(200,  url('Index/index'),'注册成功!跳转到主页'));
            return $msg = "注册成功" . "<a href='../Index/index'>首页</a>";
        }
    }

    /**
     * 登陆操作
     * @return type
     */
    public function doLogin() {
        if (request()->ispost()) {
            #获取表单信息
            $username = input('post.username');
            $password = input('post.password');
            #判断登陆
            $user = new userModel();
            $result = $user->findOne(['username' => $username, 'password' => md5($password)]);
            if (!$result) {
                return json(msg(-5, '', '账号不存在或密码错误'));
            }
            foreach($result as $va){
                $userInfo = $va->toArray();
            }           
            session('userName', $userInfo['username']);
            session('userId', $userInfo['id']);
            session('userStatus', 1);
          
            $this->redirect(url('Index/index'));
        }
    }

    /**
     * 退出登录操作
     */
    public function doOut() {

        #清空session
        session('userName', null);
        session('userID', NULL);
        session('userStatus', NULL);//登陆状态
//        session(null);
        $this->redirect(url('Index/index'));
    }

}
