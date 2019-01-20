<?php

namespace app\shopping\controller;

use think\Controller;
use app\shopping\model\AlbumModel;
use app\shopping\model\ProModel;

class Images extends Controller {
     // 文件上传表单
    public function index() {

        return $this->fetch();
    }

    //图片列表
    public function listProImages() {
        $res = AlbumModel::where(null)->field('p_id,id')->group('p_id')->paginate(5);
         $page = $res->render();
          $this->assign('page', $page);
        //获取p_id
        foreach ($res as $val) {
            $rows[] = $val->toArray();
        }
        for ($i = 0; $i < count($rows); $i++) {
            $result[$i] = AlbumModel::where(['p_id' => $rows[$i]['p_id']])->field('album_path')->limit(1)->select();
//           var_dump($result);exit;
            
            foreach ($result[$i] as $value) {
                $album = $value->toArray();
                 $rows[$i]['album_path'] = $album['album_path'];
            }
            $res[$i] = ProModel::where(['id' => $rows[$i]['p_id']])->field('p_name')->select();
            foreach ($res[$i] as $value) {
               $pName  = $value->toArray();
                 $rows[$i]['p_name'] = $pName['p_name'];
            }
        }
        if (!$rows) {
            $mes = to_json(mes(1005, '操作超时', ''));
            return $mes;
        }

        $this->assign('rows',$rows);

        return $this->fetch();
    }

   

    // 图片上传处理
    public function picture() {

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
//        var_dump($file);
//        exit;
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
