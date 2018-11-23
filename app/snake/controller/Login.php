<?php

// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------

namespace app\snake\controller;

use app\snake\model\RoleModel;
use app\snake\model\UserModel;
use app\snake\model\UserType;
use think\Controller;
use org\Verify;

class Login extends Controller {

    //登陆页面
    public function index() {
        return $this->fetch('/login');
    }

    //登陆操作
    public function doLogin() {
        //接收登陆页面提交的数据
        $userName = input('param.user_name');
        $password = input('param.password');
        $code = input('param.code');
        //验证数据是否符合验证规则
        $result = $this->validate(compact('userName', 'password', "code"), 'AdminValidate');
        echo $result;
    }

}
