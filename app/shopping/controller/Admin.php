<?php

namespace app\shopping\controller;

use think\Controller;
use app\shopping\model\AdminModel;

class Admin extends Controller {

    public function index() {
        $act = input('get.act');
        if ($act == 'addAdmin') {
            return $this->fetch('add_admin');
        }
        if ($act == 'editAdmin') {
            $id = input('get.id');
            $adminInfo = AdminModel::where(['id' => $id])->find();
            $row = array(
                'id' => $adminInfo['id'],
                'username' => $adminInfo['username'],
                'password' => $adminInfo['password'],
                'email' => $adminInfo['email']
            );
            $this->assign('row', $row);
            return $this->fetch('edit_admin');
        }
    }

    //添加管理员操作
    public function addAdmin() {
        //接受数据
        $arr = input('post.');
        //判断是否为空
        if(empty($arr['username'])||empty($arr['password'])||empty($arr['email'])){
            $mes = to_json(array('code'=>'1001','mes'=>'用户名|密码|邮箱不能为空'));
            return $mes;
        }
        //判断用户名是否已存在
        if(AdminModel::where(['username'=>$arr['username']])->find()){
            $mes = to_json(array('code'=>'1002','mes'=>'用户名已存在'));
            return $mes;
        }
        //判断密码长度是否符合要求
        if(strlen($arr['password'])<6 ||  strlen($arr['password'])>16){
            $mes = to_json(array('code'=>'1003','mes'=>'密码长度应为6~16字节长度'));
            return $mes;
        }
        //判断email是否符合
         if(AdminModel::where(['email'=>$arr['email']])->find()){
            $mes = to_json(array('code'=>'1004','mes'=>'该邮箱已注册'));
            return $mes;
        }
        $arr['password'] = md5(input('post.password'));
        $res = AdminModel::insert($arr);
        if ($res) {
            $mes = "添加成功!<br/><a href='../Admin/Index?act=addAdmin'>继续添加</a>|<a href='listAdmin'>查看管理员列表</a>";
        } else {
            $mes = "添加失败!<br/><a href='../Admin/Index?act=addAdmin'>重新添加</a>";
        }

        return $mes;
    }

    //管理员列表
    public function listAdmin() {
        //获取分页数据
        $list = AdminModel::where(['status' => 1])->paginate(5);
        // 获取分页显示
        $page = $list->render();
        $this->assign('rows', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function editAdmin() {
        $data = input('post.');
        $id = input('get.id');
        $data['password'] = md5($data['password']);
        $res = AdminModel::where(['id' => $id])->update($data);
        if ($res) {
            $mes = "修改成功!<a href='listAdmin'>查看管理员列表</a>";
        } else {
            $mes = "修改失败!<br/><a href='../Admin/Index?act=editAdmin&id=$id'>重新修改</a>";
        }

        return $mes;
    }

    public function delAdmin() {
        $id = input('get.id');
        $res = AdminModel::where(['id' => $id])->delete();
        if ($res) {
            $mes = "删除成功!<br/><a href='listAdmin'>返回管理员列表</a>";
        } else {
            $mes = "删除失败!<br/><a href='listAdmin'>返回管理员列表</a>";
        }
        return $mes;
    }

}
