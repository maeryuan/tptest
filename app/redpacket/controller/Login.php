<?php

namespace app\redpacket\controller;
use think\Controller;

class Login extends Controller{
    public function index(){
       
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

            #验证表单数据是否符合规定
            $validate = validate('AdminValidate');
            if (!$validate->check($data)) {
                return json(msg(-1, '', $validate->getError()));
            }
            #根据username,password查询数据库
            $user = new User();
           $result = $user->findOne(['username'=>$username,'password'=>md5($password)]);           
            #判断查询结果
            if(!$result){return json(msg(-2, '', '账号不存在或密码错误'));}
            #开启session（username）user_id
            $userInfo = objToArray($result);
           
            session('userName',$userInfo['username']);           
            session('userId',$userInfo['id']);
            #跳转主页面
            return $this->redirect("Index/index");
        }
    }
    public function loginOut(){
        session('userName',null);
        session('userId',NULL);
        return $this->redirect("Index/index");
    }
}

