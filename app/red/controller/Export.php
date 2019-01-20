<?php

/*
 * 将数据库的数据导出到excel表格中
 */

namespace app\red\controller;

use think\Controller;
use think\Loader;
use app\red\model\UserMainModel;
use think\Db;
class Export extends Controller {

    /**
     * 从数据库获取数据
     */
    public function export() {
//        $res = UserMainModel::all();
        $res = Db::table('redpacket_record')->where(['is_delete'=>0])->select();
        $rows = objToArray($res);
//        var_dump(array_keys($rows[0]));
        $this->inserExcel($rows);
    }


    /**
     * 将获取的数据插入到生成的excel表格中
     */
    public function inserExcel($rows) {
        ini_set('memory_limit', '1024M'); //设置php允许的文件大小最大值
        #1.第一步，导入PHPExcel的相关类，必须手动导入
        Loader::import('PHPExcel.PHPExcel'); //必须手动导入，否则会报PHPExcel类找不到
        Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');//导入生成表格类，必须手动导入
        $PHPExcel = new \PHPExcel(); //实例化phpexcel对象
        $PHPSheet = $PHPExcel->getActiveSheet(); //获取表格
        $PHPSheet->setTitle("demo1"); //给当前活动sheet设置名称
        #获取数据的字段名 数组
        $keyInfo = array_keys($rows[0]);
        #表格的列名
        $arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        #字段的个数
        $keyNums = count($keyInfo);
        #将字段写入excel表格中
        for ($i = 0; $i < $keyNums; $i++) {
            $PHPSheet->setCellValue($arr[$i].'1', $keyInfo[$i]);
        }
//        $PHPSheet->setCellValue("A1", "real_name")
//                ->setCellValue("B1", "password")
//                ->setCellValue("C1", "mobile")
//                ->setCellValue("D1", "reg_time")
//                ->setCellValue("E1", "update_time")
//                ->setCellValue("F1", "is_delete");
//                
        
        //表格数据   将获取的数据写入excel表格中
        foreach ($rows as $key => $val) {
            #数据从excel的第二行还是写入
            $num = $key+ 2;
          for($j = 0;$j<count($keyInfo);$j++){
              $PHPSheet->setCellValue($arr[$j] . $num, $val[$keyInfo[$j]]);
          }
//            $PHPSheet->setCellValue("A" . $num, $val['user_id'])
//                    ->setCellValue("B" . $num, $val['real_name'])
//                    ->setCellValue("C" . $num, $val['password'])
//                    ->setCellValue("D" . $num, $val['mobile'])
//                    ->setCellValue("E" . $num, $val['reg_time'])
//                    ->setCellValue("F" . $num, $val['update_time'])
//                    ->setCellValue("G" . $num, $val['is_delete']);
        }
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, "Excel2007"); //创建生成的格式
        header('Content-Disposition: attachment;filename="dome1.xlsx"'); //下载下来的表格名
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }

    public function downExcel() {
        return $this->fetch('down_excel');
    }

}
