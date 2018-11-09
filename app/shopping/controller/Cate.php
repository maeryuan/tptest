<?php

namespace app\shopping\controller;

use think\Controller;
use app\shopping\model\CateModel;

class Cate extends Controller {

    public function index() {
        $act = input('param.act');
        if ($act == 'addCate') {
            return $this->fetch('add_cate');
        }
        if ($act == 'editCate') {
            $id = input('get.id');
            if (empty($id)) {
                $mes = json_encode(array('code' => '1005', 'mes' => '操作超时'));
                return $mes;
            }
            $row = CateModel::where(['id' => $id])->find();
            if (!$row) {
                $mes = json_encode(array('code' => '1006', 'mes' => '操作失败'));
                return $mes;
            }
            $this->assign('cName', $row['c_name']);
            $this->assign('id', $id);
            return $this->fetch('edit_cate');
        }
    }

    public function addCate() {
        //接收数据
        $cateName = input('post.cName');
        //分类名不能为空
        if (empty($cateName)) {
            $mes = json_encode(array('code' => '1001', 'mes' => '分类名称不能为空'), JSON_UNESCAPED_UNICODE);
            return $mes;
        }
        //分类名称是否已存在
        if (CateModel::where(['c_name' => $cateName])->find()) {
            $mes = json_encode(array('code' => '1002', 'mes' => '该分类已存在'), JSON_UNESCAPED_UNICODE);
            return $mes;
        }
        //添加到数据库
        $res = CateModel::insert(['c_name' => $cateName]);
        //添加是否成功
        if ($res) {
            $mes = '添加成功！<a href="../Cate/index?act=addCate">继续添加</a>|&nbsp;<a href="../Cate/listCate">查看分类列表</a>';
        } else {
            $mes = '添加失败！<a href="../Cate/index?act=addCate">重新添加</a>';
        }
        return $mes;
    }

    public function editCate() {
        //接收数据
        $id = input('get.id');
        //判断是否就收到数据
        if (empty($id)) {
            $mes = json_encode(array('code' => '1005', 'mes' => '操作超时'));
            return $mes;
        }
        $cateInfo = input('post.cName');
        //验证 接收的cateInfo是否合格
        if (empty($cateInfo)) {
            $mes = json_encode(array('code' => '1006', 'mes' => '分类名不能为空'));
            return $mes;
        }
        //更新数据
        $res = CateModel::where(['id' => $id])->update(['c_name' => $cateInfo]);
        if ($res) {
            $mes = '更新成功！<a href="../Cate/listCate">查看分类列表</a>';
        } else {
            $mes = '更新失败！<a href="../Cate/index?act=editCate&id=$id ">重新操作</a>';
        }
        return $mes;
    }
    //分类列表
    public function listCate() {
        //获取分页数据
        $list = CateModel::where(null)->paginate(5);
        // 获取分页显示
        $page = $list->render();
        $this->assign('rows', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }
    //删除分类
    public function delCate(){
        $id = input('get.id');
        //判断是否就收到数据
        if (empty($id)) {
            $mes = json_encode(array('code' => '1005', 'mes' => '操作超时'));
            return $mes;
        }
        $res = CateModel::where(['id'=>$id])->delete();
        if ($res) {
            $mes = '删除成功！<a href="../Cate/listCate">返回分类列表</a>';
        } else {
            $mes = '删除失败！<a href="../Cate/listCate">返回分类列表</a>';
        }
        return $mes;
    }
}
