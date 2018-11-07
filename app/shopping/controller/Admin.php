<?php

namespace app\shopping\controller;
use think\Controller;
use app\shopping\model\AdminModel;
class Admin extends Controller{
    public function index(){
      $admin = new AdminModel();
      $data = $admin->getOne();
      echo"<pre>";
      var_dump($data);
    }
    //添加管理员操作
    public function addAdmin(){
        
        echo '添加管理员';
        return $this->fetch();
    }
    //管理员列表
    public function listAdmin(){
        echo"管理员列表";
         return $this->fetch();
    }
    public function editAdmin(){
        echo"管理员列表";
         return $this->fetch();
    }
}
