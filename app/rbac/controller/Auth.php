<?php

namespace app\rbac\controller;

use think\Controller;
use app\rbac\model\AuthModel;
class Auth extends Controller {

    public function index() {
        $act = input('param.act');
        switch ($act) {
            case 'add':
                return $this->fetch('add_auth');
            case 'edit':
                $authId = input('param.auth_id');
                $auth = AuthModel::get($authId);
                $authInfo['auth_name'] = $auth->auth_name;
                $authInfo['email'] = $auth->email;
                $authInfo['id'] = $authId;
                $this->assign('authInfo', $authInfo);

                $res = RoleModel::all();
                $row = objToArray($res);
                $this->assign('rows', $row);
                return $this->fetch('edit_auth');

            default :
                return $this->authList();
        }
    }

    /**
     * 权限列表
     * @return type
     */
    public function authList() {
        $res = AuthModel:: all();
        $row = objToArray($res);
        $this->assign('rows', $row);
        return $this->fetch('auth_list');
    }

}
