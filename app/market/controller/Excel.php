<?php

namespace app\market\controller;

use think\Controller;
use think\Db;
use think\Loader;
class Excel extends Controller {
    //导入和导出excel文件

       /**
     * 从数据库获取数据
     */
    public function export() {
        $res = Db::table('redpacket_record')->where(['is_delete' => 0])->select();
        $rows = objToArray($res);
        $this->exportExcel($rows);
    }

    /**
     * 下载页面
     * @return type
     */
     public function downExcel() {
        return $this->fetch('down_excel');
    }
     /**
     * 上传文件表单
     * @return type
     */
    public function uploadExcel() {
        return $this->fetch('import_excel');
    }
    /**
     * 将获取的数据插入到生成的excel表格中
     * 导出excel文件
     */
    public function exportExcel($rows) {
        ini_set('memory_limit', '1024M'); //设置php允许的文件大小最大值
        #1.第一步，导入PHPExcel的相关类，必须手动导入
        Loader::import('PHPExcel.PHPExcel'); //必须手动导入，否则会报PHPExcel类找不到
        Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory'); //导入生成表格类，必须手动导入
        $PHPExcel = new \PHPExcel(); //实例化phpexcel对象
        $PHPSheet = $PHPExcel->getActiveSheet(); //获取表格
        $PHPSheet->setTitle("demo"); //给当前活动sheet设置名称
        #获取数据的字段名 数组
        $keyInfo = array_keys($rows[0]);
        #表格的列名
        $arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        #字段的个数
        $keyNums = count($keyInfo);
        #将字段写入excel表格中
        for ($i = 0; $i < $keyNums; $i++) {
            $PHPSheet->setCellValue($arr[$i] . '1', $keyInfo[$i]);
        }

        //表格数据   将获取的数据写入excel表格中
        foreach ($rows as $key => $val) {
            #数据从excel的第二行开始写入
            $num = $key + 2;
            for ($j = 0; $j < count($keyInfo); $j++) {
                $PHPSheet->setCellValue($arr[$j] . $num, $val[$keyInfo[$j]]);
            }
        }
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, "Excel2007"); //创建生成的格式
        header('Content-Disposition: attachment;filename="dome1.xlsx"'); //下载下来的表格名
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }

    public function importExcel() {
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
//       Db::name('user_main')->insertAll($data);//将数据存入到数据库中

    }
}
