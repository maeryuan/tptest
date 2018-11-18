<?php

namespace app\shopping\controller;

use think\Controller;
use app\shopping\model\UserModel;
use think\Validate;
use app\shopping\validate\UserValidate;

class User extends Controller {

    public function index() {
        //接收act数据
        $act = input('get.act');
        switch ($act) {
            case 'addUser':return $this->fetch('add_user');
            case 'editUser':
                //接收数据id
                $id = input('get.id');
                $userInfo = UserModel::where(['id' => $id])->field('username,password,email,sex')->select();
                if (!$userInfo) {
                    $mes = to_json(mes(1005, '操作超时，请重新修改', ''));
                    return $mes;
                }

                $this->assign('id', $id);
                $this->assign('rows', $userInfo);
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
        //验证接收的数据,用户名和email是否符合规则       
        $validate = new Validate(['username' => 'require|max:25',
            'email' => 'require|email']);
        $result = $validate->check($userInfo);
        if (!$result) {
            $mes = to_json(mes(1005, $validate->getError(), ''));
            return $mes;
        }
        //密码是否符合规则 6~16字节长度
        if (strlen($userInfo['password']) < 6 || strlen($userInfo['password']) > 16 || empty($userInfo['password'])) {
            $mes = to_json(mes(1005, '密码不能为空，长度在6~16字节', ''));
            return $mes;
        }
        //md5()加密
        $userInfo['password'] = md5($userInfo['password']);
        //显示状态
        $userInfo['status'] = 1;
        //将数据添加数据库
        $res = UserModel::insertGetId($userInfo);
        if (!$res) {
            $mes = to_json(mes(1006, '添加用户失败，请重新添加', ''));
            return $mes;
        }
        return to_json(mes(200, '添加成功', $userInfo));
    }

    /**
     * 更新修改数据
     * @return type
     */
    public function editUser() {
        //接收数据
        $userInfo = input('post.');
        $id = input('get.id');
        //验证接收数据是否合格
        $validate = new Validate(['username' => 'require|max:25',
            'email' => 'require|email']);
        $result = $validate->check($userInfo);
        if (!$result) {
            $mes = to_json(mes(1005, $validate->getError(), ''));
            return $mes;
        }
        //密码是否符合规则 6~16字节长度
        if (strlen($userInfo['password']) < 6 || strlen($userInfo['password']) > 16 || empty($userInfo['password'])) {
            $mes = to_json(mes(1005, '密码不能为空，长度在6~16字节', ''));
            return $mes;
        }
        //md5()加密
        $userInfo['password'] = md5($userInfo['password']);
        //将数据添加数据库
        $res = UserModel::where(['id' => $id])->update($userInfo);
        if (!$res) {
            $mes = to_json(mes(1006, '添加用户失败，请重新添加', ''));
            return $mes;
        }
        return to_json(mes(200, '添加成功', $userInfo));
    }

}
