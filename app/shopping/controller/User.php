<?php
namespace app\shopping\controller;
use think\Controller;
use app\shopping\model\UserModel;
class User extends Controller{
    public function index(){
       
    }

    public function addUser(){
        return $this->fetch();
    }
     public function editUser(){
        return $this->fetch();
    }
     public function listUser(){
        return $this->fetch();
    }
}

