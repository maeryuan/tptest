<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function to_json($data){
    return json_encode($data,JSON_UNESCAPED_UNICODE);
}

/**
 * 统一返回信息
 * @param $code
 * @param $data
 * @param $msge
 */
function mes($code, $mes, $data)
{
    return compact('code', 'mes', 'data');
}