<?php

namespace app\mess\controller;

use think\Controller;
use app\mess\model\MesInfo;

class EditMess extends Controller {

    public function index() {
        #获取要修改的id
        $id = input('get.id');
        $mes = new MesInfo();
        $res = $mes->findOne(['id' => $id]);
        $this->assign('res', $res);
        return $this->fetch('editmess');
    }

    public function editInfo() {
        $title = input('post.title');
        $content = input('post.content');
        if ($title == '' || $content == ''){          
            $this->error("标题或文本内容不能为空,请重新添加",'SelMess/index');
            exit;
        }
            $addtime = date('Y-m-d H:i:s');
        $arr = array(
            'title' => $title,
            'content' => $content,
            'addtime' => $addtime,
        );
        $mes = new MesInfo();
        $res = $mes->insertInfo($arr);
        if($res){
            $this->success('修改成功！','SelMess/index');
            exit;
        }
    }
    
    public function delMess(){
          #获取要删除的id
        $id = input('get.id');
        echo "<script>alert('确定要删除吗？');</script>";
        $mes = new MesInfo();
        $res = $mes->delInfo(['id'=>$id]);
        if($res){
            $this->success('删除成功！','SelMess/index');
            exit;
        }
    }
}
