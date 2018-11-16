<?php

namespace app\shopping\controller;

use think\Controller;
use app\shopping\model\UserModel;
use app\shopping\validate\UserValidate;
class User extends Controller {

    public function index() {
        //接收act数据
        $act = input('get.act');
        switch ($act) {
            case 'addUser':return $this->fetch('add_user');
            case 'editUser':
                return $this->fetch('edit_user');
        }
    }

    /**
     * 展示用户列表
     * @return type
     */
    public function listUser() {
        //获取分页数据
        $list = UserModel::where(['status' => 1])->paginate(5);
        // 获取分页显示
        $page = $list->render();
        $this->assign('rows', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function addUser() {
        //接收数据
        $userInfo = input('post.');
        //验证接收的数据
        $user = array(
            'username'=>$userInfo['username'],
            'password'=>$userInfo['password'],
            'email'=>$userInfo['email']
        );
        $validate = new UserValidate();
        $result = $validate->check($user);
        var_dump($result);
        exit;
        if (!$this->validate($userInfo['username'], 'UserValidate')) {
            $mes = to_json(mes(1005, $this->getError(), ''));
            return $mes;
        }
        echo 'aaa';
        //将数据添加数据库
//        $res = UserModel::insertGetId($data);
        return $this->fetch();
    }

    public function editUser() {
        return $this->fetch();
    }

}
