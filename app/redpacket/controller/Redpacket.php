<?php

namespace app\redpacket\controller;
use think\Controller;

class Redpacket extends Controller{
    public function index(){
        echo '红包';
    }
    
      /**
     * 红包模板列表
     * @return type
     */
    public function redpacketList() {

        $redpacket = new RedpacketModel();
        #获取分页红包数据
        $list = $redpacket->getAllRedpacket();
        #获取当前分页页码
        $page = $list->render();
        # 把分页数据赋值给模板变量list
        $this->assign('list', $list);
        $this->assign('page', $page);
        #渲染模板输出
        return $this->fetch();
    }
    /**
     * 渲染编辑页面
     * @return type
     */
    public function toEdit(){
        #获取请求的id
        if(request()->isget()){
            $id = input('get.id');
           $redpacket = new RedpacketModel();
           $result = $redpacket->findOne(['redpacket_model_id'=>$id]);
           if(!$result){
               return $msg = '操作失败，请重新编辑';
           }
           $list = objToArray($result);
           $this->assign('list',$list);
        }
        return $this->fetch('editredpacket');
    }
    /**
     * 编辑红包操作
     * @return type
     */
    public function editRedpacket(){
            #获取要编辑的对象id
           $id = input('param.id');
           if(request()->ispost()){
               #获取提交表单数据
               $arr = input('post.');
               if($arr['rd_type']==0){
                   $arr['rate']=null;
               }elseif($arr['rd_type']==1){
                   $arr['cash']=null;
               }
               $arr['update_time'] = time();
               $redpacket = new RedpacketModel();
               $result = $redpacket->updateInfo($arr,['redpacket_model_id'=>$id]);
               if(!$result){
                   return $msg = '编辑操作失败，请重新编辑！';
               }
               return $msg = '编辑成功！'."<a href='redpacketlist'>查看红包列表</a>";
           }
        
    }
    
    /**
     * 删除红包模板操作
     * @return type
     */
    public function del(){
        #获取要删除的对象id
        $id = input('param.id');
        $redpacket = new RedpacketModel();
        $result = $redpacket->delRedpacket(['redpacket_model_id'=>$id]);
        if(!$result){
            return $msg = '删除操作失败！';
        }
    }
    /**
     * 渲染添加页面
     * @return type
     */
    public function toAddRedpacket(){
        return $this->fetch('addRedpacket');
    }
    /**
     * 添加操作
     * @return type
     */
    public function addRedpacket(){
        if(request()->ispost()){
            #获取表单数据
            $arr = input('post.');
             if($arr['rd_type']==0){
                   $arr['rate']=null;
               }elseif($arr['rd_type']==1){
                   $arr['cash']=null;
               }
               $arr['create_time'] = time();
               $redpacket = new RedpacketModel();
               #添加数据到数据库，并返回自增id
               $id= $redpacket->insertRedpacket($arr);
               if(!$id){
                   return $msg = '添加操作失败，请重新添加！';
               }
               return $msg = '添加成功！'."<a href='redpacketlist'>查看红包列表</a>"."<br/>"."<a href='toAddRedpacket'>继续添加</a>";
        }
    }
    /**
     * 渲染到可发送红包列表页面
     */
    public function toSend(){
        return $this->redpacketList();
    }
    /**
     * 渲染到编辑发送信息页面
     */
    public function editSend(){
      #获取要发送的红包id
      $id = input('param.id');
      $redpacket = new RedpacketModel();
      $result = $redpacket->findOne(['redpacket_model_id'=>$id]);
      if($result){
          $list = objToArray($result);
          $this->assign('list',$list);
      }
        return $this->fetch('editSend');
    }

    public function sendRedpacket(){
        $id = input('param.id');
        $num = input('param.num');
        session('id:'."$id",$num);
        #生成随机二维码图片
        $str = 'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM';
        str_shuffle($str);
        $name=substr(str_shuffle($str),0,4);
       echo $name;
    }
    public function countSend(){
        #查询数据库，表redpacket_record中红包发放的记录
        $record = new RedpacketRecord();
       $result =  $record->groupRecord();
       var_dump(objToArray($result));
    }
}

