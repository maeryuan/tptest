<?php

namespace app\shopping\validate;

use think\Validate;
class UserValidate extends Validate{
    protected $user = [
         'username'  =>  'require|max:25',
        'password'  =>  'require|min:6', 
        'password'  =>  'require|max:16', 
        'email' =>  'email',
    ];
    protected $message  =   [
        'username.require' => '名称必须',
        'username.max'     => '名称最多不能超过25个字符',        
        'password.min'  => '密码最小6位字符',
        'password.max'  => '密码最大16位字符',
        'email'        => '邮箱格式错误',    
    ];
}