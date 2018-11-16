<?php

namespace app\shopping\controller;

use think\Controller;
use app\shopping\model\ProModel;
use app\shopping\model\CateModel;
use app\shopping\model\AlbumModel;
//use app\shopping\common;

class Pro extends Controller {

    /**
     * 接收
     * @return type
     */
    public function index() {
        $act = input('get.act');
        switch ($act) {
            case 'addPro':
                //获取分类名称，id
                $rows = CateModel::all();
                $this->assign('rows', $rows);
                return $this->fetch('add_pro');

            case 'editPro':
                $id = input('get.id');
                if (!isset($id)) {
                    $mes = to_json(array('code' => 1005, 'mes' => '操作超时'));
                    return $mes;
                }
                $res = ProModel::where(['id' => $id])->find();
                if (!$res) {
                    $mes = to_json(array('code' => 1006, 'mes' => '服务器繁忙，请多试几次'));
                    return $mes;
                }
                $rows = CateModel::where(['id' => $res['c_id']])->select();
                $proInfo = array(
                    'id' => $res['id'],
                    'p_name' => $res['p_name'],
                    'c_id' => $res['c_id'],
                    'p_sn' => $res['p_sn'],
                    'p_num' => $res['p_num'],
                    'i_price' => $res['i_price'],
                    'm_price' => $res['m_price'],
                    'p_desc' => $res['p_desc']
                );
                $this->assign('id', $res['id']);
                $this->assign('proInfo', $proInfo);
                $this->assign('rows', $rows);
                return $this->fetch('edit_pro');
        }
    }

    /**
     * 添加商品，上传图片，生成缩略图
     * @return type
     */
    public function addPro() {
        //接收数据
        $proInfo = input('post.');

        // 图片上传处理
        // 获取表单上传文件 例如上传了001.jpg
        $fileInfo = request()->file('thumbs');
        if (!$fileInfo) {
            $mes = to_json(mes(1005, '请选择图片', 0));
            return $mes;
        }
        //添加数据

        foreach ($fileInfo as $file) {


            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate(['size' => 15678 * 10, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            if (!$info) { // 上传失败获取错误信息
                $mes = to_json(mes(1007, $file->getError(), $file));
                return $mes;
            }
            // 成功上传后 获取上传信息               
            //存入相对路径/upload/日期/文件名
            $data = DS . 'uploads' . DS . $info->getSaveName();
            //模板变量赋值
            $this->assign('image', $data);
            $res = ProModel::insertGetId($proInfo);
            if (!$res) {
                $mes = to_json(mes('1005', '添加失败，请重新添加', null));
                return $mes;
            }
            $albumInfo = array(
                'p_id' => $res,
                'album_path' => $data
            );
            AlbumModel::insertGetId($albumInfo);
        }
        $mes = to_json(mes(200, '添加成功', true));

        return $mes;
    }
    
    /**
     * 商品列表
     * @return type
     */
    public function listPro() {

        //获取分页数据
        $rows = ProModel::where(null)->paginate(5);

        // 获取分页显示
        $page = $rows->render();
        //查询分类信息
        $cateInfo = CateModel::all();
//        var_dump($cateInfo);exit;
        $this->assign('cateInfos', $cateInfo);
        $this->assign('rows', $rows);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function editPro() {
        //接收数据
        $proInfo = input('post.');
        //验证数据
        //将数据更新到数据库
        $id = input('get.id');
        $res = ProModel::where(['id' => $id])->update($proInfo);
        if ($res) {
            $mes = to_json(mes(200, '修改成功', $proInfo));
        } else {
            $mes = to_json(mes(1001, '编辑失败，请重新修改', null));
        }
        return $mes;
    }

    public function delPro() {
        //接收商品id
        $id = input('get.id');
        //判断是否接收到数据
        if (!isset($id)) {
            $mes = to_json(array('code' => 1005, 'mes' => '操作超时'));
            return $mes;
        }
        //根据id，删除操作
        $res = ProModel::where(['id' => $id])->delete();
        if (isset($res)) {
            $mes = to_json(mes(200, '删除成功', 1));
        } else {
            $mes = to_json(mes(1001, '删除操作超时，请重新操作', null));
        }
        return $mes;
    }

}
