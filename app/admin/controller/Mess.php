<?php

namespace app\admin\controller;
use think\Controller;
use app\admin\model\MesInfo;
class Mess extends Controller{
    public function listMess(){
            #获取留言信息
        // 查询状态为1的用户数据 并且每页显示2条数据
        $list = MesInfo::where(NULL)->paginate(2);
        // 获取分页显示
        $page = $list->render();
        $this->assign('res', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }
     public function editToId() {
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
            $this->error("标题或文本内容不能为空,请重新添加",'listMess');
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
            $this->success('修改成功！','listMess');
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
            $this->success('删除成功！','listMess');
            exit;
        }
    }
}

