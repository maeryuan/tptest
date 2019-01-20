<?php

namespace app\rbac\controller;

use think\Controller;
use app\rbac\model\UserModel;
use app\rbac\model\RoleModel;

class User extends Controller {

    public function index() {

        $act = input('param.act');
        switch ($act) {
            case 'add':
                return $this->fetch('add_user');
            case 'edit':
                $userId = input('param.user_id');
                $user = UserModel::get($userId);
                $userInfo['user_name'] = $user->user_name;
                $userInfo['email'] = $user->email;
                $userInfo['id'] = $userId;
                $this->assign('userInfo', $userInfo);

                $res = RoleModel::all();
                $row = objToArray($res);
                $this->assign('rows', $row);
                return $this->fetch('edit_user');

            default :
                return $this->userList();
        }
    }

    /**
     * 用户列表
     * @return type
     */
    public function userList() {
        $res = UserModel:: all();
        $row = objToArray($res);
        $this->assign('rows', $row);
        return $this->fetch('user_list');
    }

    /**
     * 添加用户，返回影响的条数
     */
    public function addUser() {
        $arr = input('param.');
        $user = new UserModel();
        $user->user_name = $arr['user_name'];
        $user->email = $arr['email'];
        $res = $user->save();
        if (!$res) {
            return json(msg(401, '', '操作失败，请重新添加！'));
        }
        return $this->redirect('index');
    }

    public function editUser() {
        $arr = input('param.');
        var_dump($arr);
        $user = UserModel::get($arr['user_id']);
        $user->r_id = $arr['r_id'];
        $user->save();
        return $this->redirect('index');
    }

}
