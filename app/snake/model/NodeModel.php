<?php

namespace app\snake\model;
use think\Model;

class NodeModel extends Model{
     /**
     * 根据节点数据获取对应的菜单
     * @param $nodeStr
     */
    public function getMenu($nodeStr = '')
    {
        if(empty($nodeStr)){
            return [];
        }
        // 超级管理员没有节点数组 * 号表示
        $where = '*' == $nodeStr ? 'is_menu = 2' : 'is_menu = 2 and id in(' . $nodeStr . ')';

        $result = $this->field('id,node_name,type_id,control_name,action_name,style')
            ->where($where)->select();
        $menu = prepareMenu($result);

        return $menu;
    }

}

