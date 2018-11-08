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
            return $this->fetch('edit_admin');
        }
    }

    //添加管理员操作
    public function addAdmin() {

        echo '添加管理员';
        $arr = input('post.');
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
       $list = AdminModel::where(['status'=>1])->paginate(5);
        // 获取分页显示
        $page = $list->render();
       $this->assign('rows',$list);
       $this->assign('page',$page);
       return $this->fetch();
    }

    public function editAdmin() {
        echo"管理员列表";
        return $this->fetch();
    }

    public function delAdmin(){
        $id = input('get.id');
        $res = AdminModel::where(['id'=>$id])->delete();
        if($res){
             $mes = "删除成功!<br/><a href='listAdmin'>查看管理员列表</a>";
        }
        return $mes;
    }
}
