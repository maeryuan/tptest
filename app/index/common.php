<?php

/**
 * 统一返回信息
 * @param $code
 * @param $data
 * @param $msg
 */
function msg($code, $data, $msg)
{
    return compact('code', 'data', 'msg');
}