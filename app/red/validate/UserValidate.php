<?php

namespace app\shopping\validate;

use think\Validate;

class UserValidate extends Validate {

    protected $user = [
        'username' => 'require|max:25',
        'email' => 'email',
    ];
    protected $message = [
        'username.require' => '名称必须',
        'username.max' => '名称最多不能超过25个字符',
        'email' => '邮箱格式错误',
    ];

}
