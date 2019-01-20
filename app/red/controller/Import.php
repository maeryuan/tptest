<?php
//导入excel文件
namespace app\red\controller;

use think\Controller;
use think\Loader;
use app\red\controller\Redpacket;
class Import extends Controller {

    /**
     * 上传文件表单
     * @return type
     */
    public function index() {
        return $this->fetch('import_excel');
    }
    public function import() {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('excel');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if (!$file) {
            // 上传失败获取错误信息
            return json(msg(-1, '', '上传文件不存在'));
        }
        
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if (!$info) {
            // 上传失败获取错误信息
            return json(msg(-1, '', $file->getError()));
        }
        // 成功上传后 获取上传信息
        // 输出 jpg
        $ext = $info->getExtension();
        $arr = ['xls','xlsx','csv'];
        if(!in_array($ext, $arr)){
            return json(msg(-2, '', '上传文件格式不正确'));
        }
        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        $fileName = $info->getSaveName();
        $filePath = ROOT_PATH . 'public' . DS . 'uploads' . '\\' . $fileName;

        Loader::import('PHPExcel.PHPExcel');
        Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        #创建一个reader对象
        $Reader = \PHPExcel_IOFactory::createReader('Excel2007');
        #读取文件绝对路径下的filename，并设置编码
        $PHPExcel = $Reader->load($filePath, $encode = 'utf-8');
        echo "<pre>";
        $excel_array = $PHPExcel->getsheet(0)->toArray();   //转换为数组格式

        array_shift($excel_array);  //删除第一个数组(标题);
        #处理读取excel表格导入的数据，存入一个多维数据中
        $data = [];
        $red = new Redpacket();
        foreach ($excel_array as $k => $v) {
            $data[$k]['user_id'] = $v[0];
            $data[$k]['real_name'] = $v[1];
            $data[$k]['password'] = $v[2];
            $data[$k]['mobile'] = $v[3];
            $data[$k]['reg_time'] = $v[4];
            $data[$k]['update_time'] = $v[5];
            $data[$k]['is_delete'] = $v[6];
            
            #读取一条数据，发送一条消息
            $red->send( [
                'user_id'=> $data[$k]['user_id'],
                'phoneNumber'=>$data[$k]['mobile'],
                    'redpacket_model_id'=>13
            ]);
        }
//        return json(msg(200, $data, '读取excel表格获取的数据'));
//       Db::name('user_main')->insertAll($data);

    }



}
