<?php

namespace app\shopping\model;

use think\Model;

class AlbumModel extends Model {
    protected $table = 'imooc_album';
    /*
     * 插入缩略图信息到数据库imoocAilbum表
     * @param  array    $data   缩略图信息
     * @return Array
     */
    public function addAlbum($data) {
       
       return $this->insert($data);
    }
    /*
     * 根据pid获取所有图片信息
     * @param   $pid    商品id
     * @return   Array
     */
    public function getAllImgByProId($pid){
        
       return $this->where('pid',$pid)->select();
    }
    /**
     * 根据pid删除图片信息
     * @param int $pid
     * @return int
     */
    
    public function delProImgByProId($pid){
        
       return $this ->where('pid',$pid)->delete();
    }
}
