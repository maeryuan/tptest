<?php

namespace app\mess\controller;

use think\Controller;
use app\mess\model\MesInfo;

class AddMess extends Controller {

    
    public function addmess(){
        return $this->fetch('addmess');
    }

    public function add() {
//接收数据
//$title , $content ,time
        $title = input('post.title');
        $content = input('post.content');
        $addTime = date("Y-m-d H:i:s");
        $mes = new MesInfo();
//对数据进行验证
        if ($title == '' || $content == '') {
            echo "<script>alert('标题或内容不能为空');
        window.location.href='addmess.html';</script>"; //跳转
            exit;
        }
//插入数据到数据库
//4.拼接要插入的数据
        $data = array(
            'title' => $title,
            'content' => $content,
            'addtime' => $addTime
        );
//5.调用函数，插入数据
        $res = $mes ->insertInfo($data);
//6.判断是否成功，成功resource,失败false
        if (!$res) {
            echo "<script>alert('添加失败');
        window.location.href='addmess.html';</script>";
        } else {
            echo "<script>alert('添加成功');
        window.location.href='../Index/index';</script>";
        }
    }

}
