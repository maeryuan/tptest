<?php

namespace app\shopping\controller;
use think\Controller;
use app\shopping\model\ProModel;
use app\shopping\model\CateModel;
//use app\shopping\common;

class Pro extends Controller{
    public function index(){
        $act = input('get.act');
        switch ($act){
            case 'addPro':
                //获取分类名称，id
                $rows = CateModel::all();
                $this->assign('rows',$rows);
                return $this->fetch('add_pro');
                
            case 'editPro':
                $id = input('get.id');
                if(empty($id)){
                    $mes = to_json(array('code'=>1005,'mes'=>'操作超时'));
                    return $mes;
                }
                $res = ProModel::where(['id'=>$id])->find();
                if(!$res){
                    $mes = to_json(array('code'=>1006,'mes'=>'服务器繁忙，请多试几次'));
                    return $mes;
                }
                $c_name = CateModel::where(['id'=>$res['c_id']])->filed('c_name');
                $rows = array(
                    'id'=>$res['id'],
                    'p_name'=>$res['p_name'],
                    'c_name'=>$c_name,
                    'is_show'=>$res['is_show'],
                    'pub_time'=>$res['pub_time'],
                    'i_price'=>$res['i_price']
                );
                $this->assign('rows',$rows);
                return $this->fetch('edit_pro');
        }
    }

    public function addPro(){
        //接收数据
        $proInfo = input('post.');
        
//        $res = ProModel::insert();
    }
    
    public function listPro(){
        $rows = ProModel::all();
        $this->assign('rows',$rows);
        return $this->fetch();
    }
    
    public function editPro(){
        return $this->fetch();
    }
    public function delPro(){
        echo 'delPro';
    }
    
     // 图片上传处理
    public function picture() {

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        //校验器，判断图片格式是否正确
        if (true !== $this->validate(['image' => $file], ['image' => 'require|image'])) {
            $this->error('请选择图像文件');
        } else {
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                // 成功上传后 获取上传信息               
                //存入相对路径/upload/日期/文件名
                $data = DS . 'uploads' . DS . $info->getSaveName();
                //模板变量赋值
                $this->assign('image', $data);
//                return $this->fetch('show_imgs');
                return $this->fetch('index');
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
}

