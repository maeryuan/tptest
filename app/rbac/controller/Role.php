<?php

namespace app\rbac\controller;

use think\Controller;
use app\rbac\model\RoleModel;
use app\rbac\model\AuthModel;

class Role extends Controller{
    public function index(){
            $act = input('param.act');
        switch ($act) {
            case 'add':
                return $this->fetch('add_role');
            case 'edit':
                echo 'edit';
                break;
            case 'setConf':
                $roleId = input('param.role_id');
                $role  = RoleModel::get($roleId);
                $roleName = $role->role_name;
                $this->assign('rId',$roleId);
                $this->assign('roleName',$roleName);
                $this->assign('role_id',$roleId);
                $arr = objToArray(AuthModel::all());
                $this->assign('rows',$arr);
               return $this->fetch('role_to_auth');
            default :
                return $this->roleList();
        }
    }
    
    public function roleList(){
         $res = RoleModel::all();
        $row = objToArray($res);
        $this->assign('rows', $row);
        return $this->fetch('role_list');
    }
    /**
     * 设置角色权限，添加权限id ，a_id
     */
    public function setConf(){
        $arr = input('param.');
        var_dump($arr);
    }
}

